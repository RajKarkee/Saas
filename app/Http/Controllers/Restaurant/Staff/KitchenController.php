<?php

namespace App\Http\Controllers\Restaurant\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Events\OrderCompleted;


class KitchenController extends Controller
{
    private ?int $staffId = null;
    private ?int $restaurantId = null;

    private function initStaffContext(){
        $this->staffId = Auth::guard('staff')->id();
        $this->restaurantId=DB::table('staff')->where('id', $this->staffId)->value('restaurant_id');
    }
    public function index(Request $request)
    {


        $ordertype = $this->getOrders();
        $orders = $ordertype->whereIn('status', [ 'accepted']);
        $stats = [
            'pending'  => $ordertype->where('status', 'pending')->count(),
            'accepted' => $ordertype->where('status', 'accepted')->count(),
            'cooking'  => $ordertype->where('status', 'cooking')->count(),
            'cooked'   => $ordertype->where('status', 'cooked')->count(),
            'ready'    => $ordertype->where('status', 'ready')->count(),
        ];


        return view('kitchen.layout.app', compact('orders', 'stats'));
    }
    private function getOrders()
    {
        $this->initStaffContext();
        $orders = DB::table('orders')
            ->select('orders.id', 'orders.created_at', 'orders.status', 'orders.notes','orders.accepted_at')
            ->where('orders.restaurant_id', $this->restaurantId)
            ->orderByDesc('orders.created_at')
            ->get();

        $orderIds = $orders->pluck('id');

        $itemsByOrder = DB::table('order_items')
            ->whereIn('order_items.order_id', $orderIds)
            ->leftJoin('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->select('order_items.order_id', 'order_items.quantity as quantity', 'menu_items.name as item_name')
            ->get()
            ->groupBy('order_id');

        return $orders->map(function ($order) use ($itemsByOrder) {
            $items = $itemsByOrder->get($order->id, collect())->values();
            $order->items = $items;
            $order->quantity = (int) ($items->sum('quantity'));
            $order->status = $this->mapDbToUiStatus($order->status);
            $order->time_ago = $order->accepted_at ? Carbon::parse($order->accepted_at)->diffForHumans() : null;
            return $order;
        });
    }

    public function show(Request $request, int $id)
    {
        $order = DB::table('orders')
            ->where('orders.id', $id)
            ->select('orders.*')
            ->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $items = DB::table('order_items')
            ->where('order_items.order_id', $id)
            ->leftJoin('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->select('order_items.quantity as quantity', 'menu_items.name as item_name')
            ->get();

        $payload = [
            'id'           => $order->id,
            'order_number' => '#ORD-' . $order->id,
            'status'       => $this->mapDbToUiStatus($order->status),
            'notes'        => $order->notes,
            'time_ago'     => Carbon::parse($order->accepted_at)->diffForHumans(),
            'items'        => $items,
        ];

        return response()->json(['success' => true, 'order' => $payload]);
    }

    public function updateStatus(Request $request, int $id)
    {


        $this->initStaffContext();

        // $request->validate(['status' => 'required|in:pending,cooking,ready']);





        DB::table('orders')->where('id', $id)->update([
            'status' => 'cooking',
            'cooking_started_at' => now(),
            'updated_at' => now(),
        ]);
          $order=DB::table('orders')->where('id', $id)->first();
          $items=DB::table('order_items')
          ->where('order_items.order_id', $id)
          ->leftJoin('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
          ->select('order_items.quantity as quantity', 'menu_items.name as item_name')
          ->get();
          $order->items=$items;

        return view('kitchen.cooking', compact('order'));
    }
    public function cooked(Request $request, int $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        DB::table('orders')->where('id', $id)->update([
            'status' => 'cooked',
            'cooked_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch updated order and return its UI-mapped status so frontend can update immediately
        $order = DB::table('orders')->where('id', $id)->first();
        $uiStatus = $this->mapDbToUiStatus($order->status);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as cooked',
            'order' => [
                'id' => $order->id,
                'status' => $uiStatus,
            ],
        ]);
    }
    public function cookingcom(Request $request, int $id)
    {

        DB::table('orders')->where('id', $id)->update([
            'status' => 'cooked',
            'cooked_at' => now(),
            'updated_at' => now(),
        ]);
        $order = DB::table('orders')->where('id', $id)->first();

        return view('kitchen.completed',compact('order'))->with('success', 'Order marked as ready.');
    }

    public function complete(Request $request, int $id)
    {
        $this->initStaffContext();
        if($request->isMethod('get')){
            $order = DB::table('orders')->where('id', $id)->first();
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }
            DB::table('orders')->where('id', $id)->update([
                'status' => 'completed',
                'updated_at' => now(),
                'completed_at' => now(),
            ]);

            event(new OrderCompleted($order,$this->restaurantId));
            return redirect()->route('restaurant.kitchen.index')->with('success', 'Order completed successfully.');
        }
        $order = DB::table('orders')->where('id', $id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        DB::table('orders')->where('id', $id)->update([
            'status' => 'completed',
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Order completed']);
    }

    public function all(Request $request)
    {
        $orders = $this->getOrders();
        $stats = [
            'pending' => $orders->where('status', 'pending')->count(),
            'accepted' => $orders->where('status', 'accepted')->count(),
            'cooking' => $orders->where('status', 'cooking')->count(),
            'cooked'  => $orders->where('status', 'cooked')->count(),
            'completed' => $orders->where('status', 'completed')->count(),
        ];
        return response()->json(['success' => true, 'stats' => $stats]);
    }

    private function mapDbToUiStatus(string $status): string
    {
        return match ($status) {
            'accepted'  => 'accepted',
            'cooking' => 'cooking',
            'cooked'  => 'cooked',
            'completed'   => 'completed',
            default      => 'pending',
        };
    }

    private function mapUiToDbStatus(string $status): string
    {
        return match ($status) {
            'cooking' => 'cooking',
            'cooked'  => 'cooked',
            'completed'   => 'completed',
            default   => 'pending',
        };
    }
    public function cookingall(Request $request)
    {


        $ordertype = $this->getOrders();
        $orders = $ordertype->whereIn('status', [ 'cooking']);



        return view('kitchen.cookingall', compact('orders'));
    }
    public function cookedall(Request $request)
    {


        $ordertype = $this->getOrders();
        $orders = $ordertype->whereIn('status', [ 'cooked']);



        return view('kitchen.cookedall', compact('orders'));
    }
}

