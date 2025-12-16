@extends('kitchen.layout.status')
@section('content')
    <div class="row g-3" id="cookingContainer">
        @if (isset($order) && $order)
            {{-- Single order provided by controller (e.g. after updateStatus) --}}
            @if (($order->status ?? '') === 'cooking')
                <div class="col-12 col-md-6">
                    <div class="cooking-card" data-order-id="{{ $order->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="order-number">#{{ $order->id }}</div>
                                <div class="order-time">{{ $order->time_ago ?? '' }}</div>
                            </div>
                            <div>
                                <span class="status-badge status-cooking">COOKING</span>
                            </div>
                        </div>

                        <div class="order-items">
                            @foreach ($order->items ?? [] as $item)
                                <div class="item-row">
                                    <div>
                                        <div class="fw-600">{{ $item->item_name ?? 'Item' }}</div>
                                        @if (!empty($item->notes))
                                            <div class="text-muted small">{{ $item->notes }}</div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge-qty">×{{ $item->quantity ?? 1 }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (!empty($order->notes))
                            <div class="mt-2 p-2"
                                style="background:#fffbea;border-left:3px solid var(--warning-color);border-radius:6px;">
                                <strong style="color:var(--warning-color)">Note:</strong> {{ $order->notes }}
                            </div>
                        @endif

                        <div class="mt-3 text-end">
                            <a href="{{ route('restaurant.kitchen.cookingcom', $order->id) }}"
                                class="btn btn-success">Cooked</a>


                        </div>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="empty">
                        <i class="bi bi-inbox" style="font-size:48px"></i>
                        <h5 class="mt-3">Order is not in cooking state</h5>
                        <p>This order has status: {{ $order->status }}</p>
                    </div>
                </div>
            @endif
        @else
            @php $found = false; @endphp
            @foreach ($orders ?? [] as $order)
                @if (($order->status ?? '') === 'cooking')
                    @php $found = true; @endphp
                    <div class="col-12 col-md-6">
                        <div class="cooking-card" data-order-id="{{ $order->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="order-number">#{{ $order->id }}</div>
                                    <div class="order-time">{{ $order->time_ago ?? '' }}</div>
                                </div>
                                <div>
                                    <span class="status-badge status-cooking">COOKING</span>
                                </div>
                            </div>

                            <div class="order-items">
                                @foreach ($order->items ?? [] as $item)
                                    <div class="item-row">
                                        <div>
                                            <div class="fw-600">{{ $item->item_name ?? 'Item' }}</div>
                                            @if (!empty($item->notes))
                                                <div class="text-muted small">{{ $item->notes }}</div>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="badge-qty">×{{ $item->quantity ?? 1 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (!empty($order->notes))
                                <div class="mt-2 p-2"
                                    style="background:#fffbea;border-left:3px solid var(--warning-color);border-radius:6px;">
                                    <strong style="color:var(--warning-color)">Note:</strong> {{ $order->notes }}
                                </div>
                            @endif

                            <div class="mt-3 text-end">
                                <button class="btn btn-sm btn-primary" onclick="viewOrderDetails({{ $order->id }})"><i
                                        class="bi bi-eye"></i>
                                    View</button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            @unless ($found)
                <div class="col-12">
                    <div class="empty">
                        <i class="bi bi-inbox" style="font-size:48px"></i>
                        <h5 class="mt-3">No cooking items right now</h5>
                        <p>All orders are either pending or ready.</p>
                    </div>
                </div>
            @endunless
        @endif
    </div>
@endsection
