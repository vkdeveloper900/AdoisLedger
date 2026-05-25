@php
    $isDairy    = in_array($bill->type->value, ['dairy_sale', 'dairy_purchase']);
    $isPurchase = $bill->type->value === 'dairy_purchase';
    $biz        = $bill->business;
    $party      = $bill->customer;
@endphp
@extends('layouts.auth')

@section('title', $bill->bill_number . ' — ' . $biz->name)

@push('styles')
<style>
    body { background: #f5f5f9 !important; }
    .invoice-wrap { max-width: 860px; margin: 40px auto; padding: 0 16px 60px; }
    .invoice-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); overflow: hidden; }
    .invoice-header { background: linear-gradient(135deg, #696cff 0%, #9155fd 100%); padding: 32px 40px; color: #fff; }
    .invoice-header .biz-name { font-size: 22px; font-weight: 700; letter-spacing: .3px; }
    .invoice-header .biz-meta { font-size: 13px; opacity: .85; margin-top: 4px; line-height: 1.7; }
    .invoice-header .inv-number { font-size: 28px; font-weight: 700; text-align: right; }
    .invoice-header .inv-meta { font-size: 13px; opacity: .85; text-align: right; margin-top: 4px; line-height: 1.7; }
    .invoice-body { padding: 36px 40px; }
    .section-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #696cff; margin-bottom: 6px; }
    .party-name { font-size: 16px; font-weight: 600; color: #32325d; }
    .party-meta { font-size: 13px; color: #6c757d; line-height: 1.7; margin-top: 2px; }
    .items-table { width: 100%; border-collapse: collapse; margin: 28px 0 0; }
    .items-table thead tr { background: #f5f5f9; }
    .items-table thead th { padding: 10px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #6c757d; border-bottom: 2px solid #eee; }
    .items-table tbody td { padding: 12px 14px; font-size: 13px; color: #32325d; border-bottom: 1px solid #f0f0f0; }
    .items-table tbody tr:last-child td { border-bottom: none; }
    .items-table tfoot td { padding: 10px 14px; font-size: 13px; }
    .totals-box { background: #f8f8ff; border-radius: 8px; padding: 20px 24px; }
    .totals-box .row-label { color: #6c757d; font-size: 13px; }
    .totals-box .row-value { font-size: 13px; font-weight: 600; color: #32325d; }
    .totals-box .total-row .row-label,
    .totals-box .total-row .row-value { font-size: 16px; font-weight: 700; color: #696cff; border-top: 2px solid #e0e0ff; padding-top: 10px; margin-top: 6px; }
    .totals-box .balance-row .row-value { color: #dc3545; }
    .totals-box .settled-row .row-value { color: #28a745; }
    .bank-box { border: 1px solid #e8e8ff; border-radius: 8px; padding: 16px 20px; }
    .bank-item { font-size: 12px; color: #6c757d; }
    .bank-item strong { display: block; color: #32325d; font-size: 13px; }
    .invoice-footer { border-top: 1px solid #f0f0f0; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; background: #fafafa; }
    .powered-badge { font-size: 11px; color: #aaa; }
    .powered-badge span { color: #696cff; font-weight: 600; }
    @media (max-width: 600px) {
        .invoice-header { padding: 24px 20px; }
        .invoice-body { padding: 24px 20px; }
        .invoice-footer { padding: 16px 20px; flex-direction: column; gap: 8px; }
    }
</style>
@endpush

@section('content')
<div class="invoice-wrap">

    {{-- Header --}}
    <div class="invoice-card">
        <div class="invoice-header">
            <div class="row align-items-start">
                <div class="col-7">
                    <div class="biz-name">{{ $biz->name }}</div>
                    <div class="biz-meta">
                        @if($biz->address){{ $biz->address }}@if($biz->city), {{ $biz->city }}@endif<br>@endif
                        @if($biz->phone)📞 {{ $biz->phone }}@endif
                        @if($biz->email) &nbsp;|&nbsp; ✉ {{ $biz->email }}@endif
                    </div>
                </div>
                <div class="col-5">
                    <div class="inv-number">{{ $isPurchase ? 'Purchase' : 'Invoice' }}</div>
                    <div class="inv-meta">
                        <strong style="font-size:15px;">{{ $bill->bill_number }}</strong><br>
                        {{ $bill->date->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-body">

            {{-- Bill To + Summary --}}
            <div class="row mb-4">
                <div class="col-sm-6 mb-4 mb-sm-0">
                    <div class="section-label">{{ $isPurchase ? 'Vendor / Farmer' : 'Bill To' }}</div>
                    <div class="party-name">{{ $party?->name ?? '—' }}</div>
                    <div class="party-meta">
                        @if($party?->mobile)📞 {{ $party->mobile }}<br>@endif
                        @if($party?->address){{ $party->address }}@endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="totals-box">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="row-label">Subtotal</span>
                            <span class="row-value">Rs.{{ number_format($bill->subtotal, 2) }}</span>
                        </div>
                        @if($bill->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="row-label">Discount</span>
                            <span class="row-value" style="color:#dc3545;">− Rs.{{ number_format($bill->discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between total-row">
                            <span class="row-label">Total</span>
                            <span class="row-value">Rs.{{ number_format($bill->total_amount) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="row-label">{{ $isPurchase ? 'Paid' : 'Received' }}</span>
                            <span class="row-value" style="color:#28a745;">Rs.{{ number_format($bill->amount_received) }}</span>
                        </div>
                        @if($bill->balance > 0)
                        <div class="d-flex justify-content-between mt-1 balance-row">
                            <span class="row-label">Balance Due</span>
                            <span class="row-value">Rs.{{ number_format($bill->balance) }}</span>
                        </div>
                        @else
                        <div class="d-flex justify-content-between mt-1 settled-row">
                            <span class="row-label">Status</span>
                            <span class="row-value">✔ Fully Settled</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="section-label mt-2">Items</div>
            <div class="table-responsive">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th style="text-align:left;">Description</th>
                            @if(!$isDairy)<th style="text-align:center;">Unit</th>@endif
                            <th style="text-align:right;">Qty{{ $isDairy ? ' (L)' : '' }}</th>
                            @if($isDairy)<th style="text-align:right;">Fat %</th>@endif
                            <th style="text-align:right;">Rate</th>
                            <th style="text-align:right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->items as $i => $item)
                        <tr>
                            <td style="text-align:center; color:#aaa;">{{ $i + 1 }}</td>
                            <td>{{ $item->description }}</td>
                            @if(!$isDairy)<td style="text-align:center;">{{ $item->unit ?? '—' }}</td>@endif
                            <td style="text-align:right;">{{ number_format($item->qty, 3) }}</td>
                            @if($isDairy)<td style="text-align:right;">{{ $item->fat !== null ? number_format($item->fat, 2).'%' : '—' }}</td>@endif
                            <td style="text-align:right;">Rs.{{ number_format($item->rate, 2) }}</td>
                            <td style="text-align:right; font-weight:600;">Rs.{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Notes --}}
            @if($bill->notes)
            <div class="mt-4">
                <div class="section-label">Notes</div>
                <p class="text-muted small mb-0">{{ $bill->notes }}</p>
            </div>
            @endif

            {{-- Bank Details --}}
            @if($biz->bank_account_number)
            <div class="mt-4">
                <div class="section-label">Bank Details</div>
                <div class="bank-box">
                    <div class="row g-3">
                        @if($biz->bank_name)
                        <div class="col-6 col-sm-3">
                            <div class="bank-item"><strong>Bank</strong>{{ $biz->bank_name }}</div>
                        </div>
                        @endif
                        @if($biz->bank_holder_name)
                        <div class="col-6 col-sm-3">
                            <div class="bank-item"><strong>Account Name</strong>{{ $biz->bank_holder_name }}</div>
                        </div>
                        @endif
                        <div class="col-6 col-sm-3">
                            <div class="bank-item"><strong>Account No.</strong>{{ $biz->bank_account_number }}</div>
                        </div>
                        @if($biz->bank_ifsc_code)
                        <div class="col-6 col-sm-3">
                            <div class="bank-item"><strong>IFSC</strong>{{ $biz->bank_ifsc_code }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="invoice-footer">
            <p class="mb-0 text-muted small">Thank you for your business 🙏</p>
            <p class="mb-0 powered-badge">Powered by <span>AdoisLedger</span></p>
        </div>
    </div>

</div>
@endsection
