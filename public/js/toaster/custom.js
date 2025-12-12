

function createToast(message, type = 'error') {
    const container = document.getElementById('toastContainer') || (function () {
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
    t.textContent = message;
    t.style.padding = '10px 14px';
    t.style.marginTop = '8px';
    t.style.borderRadius = '6px';
    t.style.color = '#fff';
    t.style.background = type === 'success' ? 'linear-gradient(90deg,#34d399,#10b981)' :
        'linear-gradient(90deg,#ff6b6b,#ff3b3b)';
    container.appendChild(t);
    setTimeout(() => t.remove(), 5000);
}

$(document).ready(function () {



    const tooltipTriggerList = [].slice.call(document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
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
