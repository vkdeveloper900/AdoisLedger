{{-- Loaded via AJAX into #billOffcanvasBody --}}
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h6 class="mb-1">Bill #{{ $transaction->bill_number }}</h6>
        <small class="text-body-secondary">{{ $transaction->date->format('d M Y') }}</small>
    </div>
    <span class="badge bg-label-{{ $transaction->balance > 0 ? 'danger' : 'success' }} rounded-pill">
        {{ $transaction->balance > 0 ? 'Due' : 'Paid' }}
    </span>
</div>

<p class="mb-1"><strong>Customer:</strong> {{ $transaction->customer->name }}</p>
@if($transaction->notes)
    <p class="mb-3 text-body-secondary small">{{ $transaction->notes }}</p>
@endif

<div class="table-responsive mb-3">
    <table class="table table-sm table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Rate</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                <td class="text-end">₹{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-light">
            <tr>
                <td colspan="3" class="text-end">Subtotal</td>
                <td class="text-end">₹{{ number_format($transaction->subtotal, 2) }}</td>
            </tr>
            @if($transaction->discount > 0)
            <tr>
                <td colspan="3" class="text-end text-danger">Discount</td>
                <td class="text-end text-danger">− ₹{{ number_format($transaction->discount, 2) }}</td>
            </tr>
            @endif
            <tr class="fw-bold">
                <td colspan="3" class="text-end">Total</td>
                <td class="text-end text-primary">₹{{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end text-success">Received</td>
                <td class="text-end text-success">₹{{ number_format($transaction->amount_received, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end text-danger">Balance Due</td>
                <td class="text-end text-danger fw-bold">₹{{ number_format($transaction->balance, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
