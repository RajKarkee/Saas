
        function createToast(message, type = 'error') {
            const container = document.getElementById('toastContainer') || (function() {
                const c = document.createElement('div');
                c.id = 'toastContainer';
                c.style.position = 'fixed';
                c.style.top = '1rem';
                c.style.right = '1rem';
                c.style.zIndex = 9999;
                document.body.appendChild(c);
                return c;
            })();

            const t = document.createElement('div');
            t.className = 'toast-item ' + type;
            t.style.minWidth = '240px';
            t.style.marginTop = '8px';
            t.style.padding = '12px 14px';
            t.style.borderRadius = '8px';
            t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.08)';
            t.style.color = '#fff';
            t.style.fontSize = '14px';
            t.style.display = 'flex';
            t.style.alignItems = 'center';
            t.style.justifyContent = 'space-between';
            t.style.background = type === 'success' ? 'linear-gradient(90deg,#34d399,#10b981)' :
                'linear-gradient(90deg,#ff6b6b,#ff3b3b)';

            const span = document.createElement('div');
            span.textContent = message;
            t.appendChild(span);
            const close = document.createElement('button');
            close.textContent = '×';
            close.style.background = 'transparent';
            close.style.border = 'none';
            close.style.color = 'rgba(255,255,255,0.9)';
            close.style.fontSize = '18px';
            close.style.cursor = 'pointer';
            close.addEventListener('click', () => t.remove());
            t.appendChild(close);
            container.appendChild(t);
            setTimeout(() => t.remove(), 6000);
        }



        // Show server flash messages as toasts
        $(document).ready(function() {
            // show server flash messages
            // Note: This code should be moved to a blade template file
            // For now, server flash messages should be passed via data attributes or inline script


            const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // $(document).on('click', '.btn-delete', function() {
            //     if (!confirm('Delete this item?')) return;
            //     const action = this.dataset.action;
            //     fetch(action, {
            //             method: 'POST',
            //             headers: {
            //                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
            //                 'X-Requested-With': 'XMLHttpRequest'
            //             },

            
            //             body: (new FormData()).append('_method', 'DELETE') || new FormData()
            //         })
            //         .then(r => r.json().catch(() => ({}))).then(data => {
            //             if (!data || data.status === 'error') {
            //                 createToast(data.message || 'Failed', 'error');
            //                 return;
            //             }
            //             $(this).closest('tr').remove();
            //             createToast(data.message || 'Deleted', 'success');
            //         }).catch(() => createToast('Failed', 'error'));
            // });
        });
        // const tooltipTriggerList = [].slice.call(document.querySelectorAll(
        //             '[data-bs-toggle="tooltip"]') tooltipTriggerList.map(function(tooltipTriggerEl) {
        //             return new bootstrap.Tooltip(tooltipTriggerEl)
        //         });
