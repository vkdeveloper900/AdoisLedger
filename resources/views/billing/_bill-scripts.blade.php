<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
const CUSTOMER_INFO_URL = '{{ route("bills.customer-info") }}';

$(function () {
    $('#customer_id').select2({ placeholder: 'Search by name or mobile…', allowClear: true, width: '100%' })
    .on('change', function () {
        const id = $(this).val();
        if (!id) { $('#customerInfoCard').addClass('d-none'); return; }
        $.getJSON(CUSTOMER_INFO_URL, { customer_id: id }, function (data) {
            const bal = data.balance;
            const balEl = $('#custBalance');
            balEl.text('₹' + Math.abs(bal).toLocaleString('en-IN'));
            balEl.removeClass('text-danger text-success text-body-secondary');
            if (bal > 0) balEl.addClass('text-danger');
            else if (bal < 0) balEl.addClass('text-success');
            else balEl.addClass('text-body-secondary');

            let html = '';
            if (data.last_transactions.length) {
                html += '<p class="small text-body-secondary mb-1 fw-semibold">Last Transactions</p><ul class="list-unstyled mb-0 small">';
                data.last_transactions.forEach(tx => {
                    const due = tx.balance > 0
                        ? `<span class="text-danger">Due ₹${Number(tx.balance).toLocaleString('en-IN')}</span>`
                        : `<span class="text-success">Paid</span>`;
                    html += `<li class="d-flex justify-content-between border-bottom py-1">
                        <span>${tx.bill_number} <small class="text-body-secondary">${tx.date}</small></span>
                        <span>₹${Number(tx.total_amount).toLocaleString('en-IN')} ${due}</span>
                    </li>`;
                });
                html += '</ul>';
            } else {
                html = '<p class="small text-body-secondary mb-0">No previous transactions.</p>';
            }
            $('#lastTxList').html(html);
            $('#customerInfoCard').removeClass('d-none');
        });
    });
    if ($('#customer_id').val()) $('#customer_id').trigger('change');
});

function recalc() {
    let subtotal = 0;
    document.querySelectorAll('#itemsContainer .item-row').forEach(row => {
        const qty  = parseFloat(row.querySelector('.item-qty')?.value)  || 0;
        const rate = parseFloat(row.querySelector('.item-rate')?.value) || 0;
        const amt  = Math.round(qty * rate * 100) / 100;
        const el   = row.querySelector('.item-amount');
        if (el) el.textContent = '₹' + amt.toFixed(2);
        subtotal += amt;
    });
    subtotal = Math.round(subtotal * 100) / 100;

    const discEl = document.getElementById('discount');
    if (discEl.dataset.manual !== '1') {
        discEl.value = (Math.round((subtotal % 1) * 100) / 100).toFixed(2);
    }

    const discount = parseFloat(discEl.value) || 0;
    const total    = Math.floor(subtotal - discount);
    const recvEl   = document.getElementById('amount_received');
    if (recvEl.dataset.manual !== '1') recvEl.value = total;

    const received = parseInt(recvEl.value) || 0;
    const balance  = Math.max(0, total - received);

    document.getElementById('displaySubtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('displayDiscount').textContent = '₹' + discount.toFixed(2);
    document.getElementById('displayTotal').textContent    = '₹' + total.toLocaleString('en-IN');
    document.getElementById('displayBalance').textContent  = '₹' + balance.toLocaleString('en-IN');
}

document.getElementById('itemsContainer').addEventListener('input', e => {
    if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-rate')) recalc();
});

document.getElementById('itemsContainer').addEventListener('click', e => {
    const btn = e.target.closest('.del-row');
    if (btn && document.querySelectorAll('#itemsContainer .item-row').length > 1) {
        btn.closest('.item-row').remove(); recalc();
    }
});

document.getElementById('discount').addEventListener('input', () => {
    document.getElementById('discount').dataset.manual = '1'; recalc();
});
document.getElementById('amount_received').addEventListener('input', () => {
    document.getElementById('amount_received').dataset.manual = '1'; recalc();
});
</script>
