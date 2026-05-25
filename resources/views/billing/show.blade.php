@extends('layouts.app')
@section('title', 'Bill ' . $bill->bill_number)

@section('content')
<div class="d-flex align-items-center mb-6">
    <a href="{{ route('bills.index') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0">{{ $bill->bill_number }}</h4>
        <small class="text-body-secondary">{{ $bill->date->format('d M Y') }} · {{ $bill->type->label() }}</small>
    </div>
    <div class="ms-auto d-flex gap-2 align-items-center flex-wrap">
        <span class="badge bg-label-{{ $bill->status === 'posted' ? 'success' : 'warning' }} rounded-pill fs-6">
            {{ ucfirst($bill->status) }}
        </span>

        {{-- PDF --}}
        <a href="{{ route('bills.pdf', $bill) }}" target="_blank"
           class="btn btn-sm btn-label-danger">
            <i class="icon-base ti tabler-file-type-pdf me-1"></i> PDF
        </a>

        {{-- WhatsApp --}}
        @php
            $publicUrl = route('bills.public', $bill->public_token);
            $phone     = $bill->customer?->mobile ? preg_replace('/\D/', '', $bill->customer->mobile) : '';
            $phone     = strlen($phone) === 10 ? '91'.$phone : $phone;
            $msg       = "*{$bill->business->name}*\n"
                       . "*Invoice: {$bill->bill_number}*\n"
                       . "Date: {$bill->date->format('d M Y')}\n\n"
                       . "*Customer:* {$bill->customer?->name}\n"
                       . ($bill->customer?->mobile ? "Mobile: {$bill->customer->mobile}\n" : '')
                       . "\n*Total:* Rs." . number_format($bill->total_amount) . "\n"
                       . "*Received:* Rs." . number_format($bill->amount_received) . "\n"
                       . ($bill->balance > 0 ? "*Balance Due:* Rs." . number_format($bill->balance) . "\n" : "*Fully Settled*\n")
                       . "\n*View Invoice:* {$publicUrl}\n\n"
                       . ($bill->business->phone ? "_{$bill->business->name} · {$bill->business->phone}_" : "_{$bill->business->name}_");
            $waUrl     = 'https://wa.me/' . ($phone ?: '') . '?text=' . rawurlencode($msg);
        @endphp
        <a href="{{ $waUrl }}" target="_blank"
           class="btn btn-sm btn-success">
            <i class="icon-base ti tabler-brand-whatsapp me-1"></i> WhatsApp
        </a>

        @if ($bill->balance > 0)
            <a href="{{ route('payments.create', ['customer_id' => $bill->customer_id]) }}"
               class="btn btn-sm btn-primary">
                <i class="icon-base ti tabler-cash me-1"></i> Receive Payment
            </a>
        @endif
    </div>
</div>

<div class="row g-6">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Items</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th class="text-end">Qty</th>
                            @if ($bill->type->value === 'dairy_sale' || $bill->type->value === 'dairy_purchase')
                                <th class="text-end">Fat%</th>
                            @endif
                            <th class="text-end">Rate</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bill->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->unit ?? '—' }}</td>
                                <td class="text-end">{{ $item->qty }}</td>
                                @if ($bill->type->value === 'dairy_sale' || $bill->type->value === 'dairy_purchase')
                                    <td class="text-end">{{ $item->fat ?? '—' }}</td>
                                @endif
                                <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                                <td class="text-end fw-medium">₹{{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Customer --}}
        <div class="card mb-6">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">Customer</p>
                <h6 class="mb-0">{{ $bill->customer?->name ?? '—' }}</h6>
                @if ($bill->customer?->mobile)
                    <small class="text-body-secondary">{{ $bill->customer->mobile }}</small>
                @endif
                @if ($bill->notes)
                    <hr class="my-3">
                    <p class="text-body-secondary small mb-1">Notes</p>
                    <p class="mb-0">{{ $bill->notes }}</p>
                @endif
            </div>
        </div>

        {{-- Totals --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-body-secondary">Subtotal</span>
                    <span>₹{{ number_format($bill->subtotal, 2) }}</span>
                </div>
                @if ($bill->discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-body-secondary">Discount</span>
                        <span class="text-danger">- ₹{{ number_format($bill->discount, 2) }}</span>
                    </div>
                @endif
                <hr class="my-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold fs-5">Total</span>
                    <span class="fw-bold fs-5 text-primary">₹{{ number_format($bill->total_amount) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-body-secondary">Received</span>
                    <span class="text-success">₹{{ number_format($bill->amount_received) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-medium">Balance Due</span>
                    <span class="fw-bold {{ $bill->balance > 0 ? 'text-danger' : 'text-success' }}">
                        ₹{{ number_format($bill->balance) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
