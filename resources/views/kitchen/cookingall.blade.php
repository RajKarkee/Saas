@extends('kitchen.layout.status')

@push('styles')
    <style>
        .cooking-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .col-12.col-md-6 {
            display: flex;
        }

        .order-items {
            flex: 1;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3" id="cookingContainer">
        @if (isset($order) && $order)

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
                            <button class="btn btn-sm btn-success btn-cooked"
                                data-order-id="{{ $order->id }}">Cooked</button>
                            <button class="btn btn-sm btn-secondary btn-completed" data-order-id="{{ $order->id }}"
                                style="display:none;">Completed</button>

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
                                <a href="{{ route('restaurant.kitchen.cookingcom', $order->id) }}"
                                    class="btn btn-success">Cooked</a>


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
    {{-- 
    @push('scripts')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.btn-cooked', function(e) {
                e.preventDefault();
                const orderId = $(this).data('order-id');
                const $btn = $(this);
                const $card = $(`.cooking-card[data-order-id="${orderId}"]`);
                $btn.prop('disabled', true).text('Saving...');
                $.ajax({
                    url: `/restaurant/kitchen/orders/${orderId}/cooked`,
                    method: 'POST',
                    dataType: 'json'
                }).done(function(res) {
                    if (res.success) {
                        // update badge
                        $card.find('.status-badge').text((res.order && res.order.status ? res.order.status :
                            'COOKED').toUpperCase()).removeClass('status-cooking').addClass('status-cooked');
                        // swap buttons
                        $btn.hide();
                        $card.find('.btn-completed').show();
                    } else {
                        alert(res.message || 'Failed to mark cooked');
                        $btn.prop('disabled', false).text('Cooked');
                    }
                }).fail(function() {
                    alert('Error contacting server');
                    $btn.prop('disabled', false).text('Cooked');
                });
            });

            $(document).on('click', '.btn-completed', function(e) {
                e.preventDefault();
                const orderId = $(this).data('order-id');
                const $btn = $(this);
                $btn.prop('disabled', true).text('Completing...');
                $.ajax({
                    url: `/restaurant/kitchen/orders/${orderId}/complete`,
                    method: 'POST',
                    dataType: 'json'
                }).done(function(res) {
                    if (res.success) {
                        window.location.href = '{{ url('/restaurant/kitchen') }}';
                    } else {
                        alert(res.message || 'Failed to complete order');
                        $btn.prop('disabled', false).text('Completed');
                    }
                }).fail(function() {
                    alert('Error contacting server');
                    $btn.prop('disabled', false).text('Completed');
                });
            });
        </script>
    @endpush --}}
@endsection
