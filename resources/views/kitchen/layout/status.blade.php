<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cooking Items</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #ecf0f1;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
        }

        .page-title {
            font-size: 1.4rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .cooking-card {
            background: #fff;
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #3498db;
        }

        .cooking-card .order-number {
            font-weight: 700;
            color: var(--primary-color);
        }

        .cooking-card .order-time {
            color: #7f8c8d;
            font-size: 0.85rem;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .badge-qty {
            background: var(--primary-color);
            color: #fff;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="page-header">
            <div class="page-title"><i class="bi bi-fire"></i> Items Cooking</div>
            <div>
                <a href="{{ route('restaurant.kitchen.index') }}" class="btn btn-sm btn-outline-secondary">Back to
                    Kitchen</a>
            </div>
        </div>

        @yield('content')
    </div>

    {{-- <!-- Modal (re-use the same id as layout) -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,var(--primary-color),#34495e);color:#fff;">
                    <h5 class="modal-title"><i class="bi bi-receipt"></i> Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"><span
                                class="visually-hidden">Loading...</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let orderModal;
        $(function() {
            orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
        });

        function viewOrderDetails(orderId) {
            $('#orderModalBody').html(
                `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`
            );
            orderModal.show();
            $.getJSON(`/restaurant/kitchen/orders/${orderId}`, function(res) {
                if (res.success) {
                    const order = res.order;
                    let itemsHtml = '';
                    order.items.forEach(i => {
                        itemsHtml +=
                            `<div class="d-flex justify-content-between py-2 border-bottom"><div><strong>${i.item_name}</strong></div><div><span class="badge bg-secondary">×${i.quantity}</span></div></div>`;
                    });
                    const notes = order.notes ? `<div class="alert alert-warning mt-3">${order.notes}</div>` :
                        '';
                    $('#orderModalBody').html(
                        `<div class="row"><div class="col-md-6"><p><strong>Order #</strong> ${order.id}</p><p><strong>Time:</strong> ${order.time_ago}</p></div><div class="col-md-6 text-end"><span class="status-badge status-${order.status}">${order.status.toUpperCase()}</span></div></div><hr><h6>Items</h6>${itemsHtml}${notes}`
                    );
                } else {
                    $('#orderModalBody').html('<div class="text-danger p-3">Failed to load order</div>');
                }
            }).fail(function() {
                $('#orderModalBody').html('<div class="text-danger p-3">Error loading order</div>');
            });
        }


        function cookedOrder(orderId) {
            const $btnCooked = $('#markCookedButton[data-order-id="' + orderId + '"]');
            const $btnComplete = $('#markCompletedButton[data-order-id="' + orderId + '"]');
            $btnCooked.prop('disabled', true).text('Saving...');
            $.ajax({
                        url: `/restaurant/kitchen/orders/${orderId}/cooked`,
                        method: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                // Update status badge on the card
                                const $card = $(`.cooking-card[data-order-id="${orderId}"] `);
                        $card.find('.status-badge').text((res.order && res.order.status ? res.order.status : 'COOKED').toUpperCase()).removeClass('status-cooking').addClass('status-cooked');

                        // hide cooked button, show completed
                        $btnCooked.hide();
                        $btnComplete.show();
                        showToast(res.message || 'Order marked as cooked', 'success');

                        // if modal is open update modal badge too
                        const $modalBadge = $('#orderModalBody').find('.status-badge');
                        if ($modalBadge.length) {
                            $modalBadge.text((res.order && res.order.status ? res.order.status : 'COOKED').toUpperCase()).removeClass('status-cooking').addClass('status-cooked');
                        }
                    } else {
                        showToast(res.message || 'Failed to mark cooked', 'danger');
                        $btnCooked.prop('disabled', false).text('Cooked');
                    }
                },
                error: function() {
                    showToast('Error marking cooked', 'danger');
                    $btnCooked.prop('disabled', false).text('Cooked');
                }
            });
        }

        // Complete order then redirect back to kitchen
        function completeOrderRedirect(orderId) {
            const $btn = $('#markCompletedButton[data-order-id="' + orderId + '"]');
            $btn.prop('disabled', true).text('Completing...');
            $.ajax({
                url: ` / restaurant / kitchen / orders / $ {
                                orderId
                            }
                            /complete`,
                            method: 'POST',
                                dataType: 'json',
                                success: function(res) {
                                    if (res.success) {
                                        showToast(res.message || 'Order completed', 'success');
                                        setTimeout(function() {
                                            window.location.href = '/restaurant/kitchen';
                                        }, 700);
                                    } else {
                                        showToast(res.message || 'Failed to complete', 'danger');
                                        $btn.prop('disabled', false).text('Completed');
                                    }
                                },
                                error: function() {
                                    showToast('Error completing order', 'danger');
                                    $btn.prop('disabled', false).text('Completed');
                                }
                        });
                } --}}

</body>

</html>
