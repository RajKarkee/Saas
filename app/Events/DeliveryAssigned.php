<?php
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class DeliveryAssigned implements ShouldBroadcastNow
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $orderPayload;
  protected $deliveryManId;

  public function __construct($orderPayload ,$deliveryManId)
  {
      $this->orderPayload = $orderPayload;
      $this->deliveryManId = $deliveryManId;
  }

  public function broadcastOn()
  {
      return new PrivateChannel('delivery-man.' . $this->deliveryManId);
  }

  public function broadcastAs()
  {
      return 'delivery.assigned';
  }
  public function broadcastWith()
  {
      return ['order' => $this->orderPayload];
  }
}