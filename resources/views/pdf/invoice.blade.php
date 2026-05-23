@php
    $isDairy    = in_array($transaction->type->value, ['dairy_sale','dairy_purchase']);
    $isPurchase = $transaction->type->value === 'dairy_purchase';
    $biz        = $transaction->business;
    $party      = $transaction->customer;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#1a1a2e; }

    .page { padding:30px 36px; }

    /* Header */
    .header-table { width:100%; }
    .biz-name  { font-size:20px; font-weight:bold; color:#696cff; margin-bottom:4px; }
    .biz-meta  { font-size:11px; color:#555; line-height:1.7; }
    .inv-title { font-size:22px; font-weight:bold; color:#333; text-transform:uppercase; letter-spacing:1px; text-align:right; }
    .inv-meta  { font-size:11px; color:#555; text-align:right; margin-top:6px; line-height:1.7; }
    .inv-meta b { color:#333; }

    /* Divider */
    .divider { border:none; border-top:2px solid #696cff; margin:16px 0; }

    /* Footer party + bank */
    .footer-label { font-size:10px; font-weight:bold; color:#696cff; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px; }
    .party-name  { font-size:13px; font-weight:bold; color:#333; }
    .party-meta  { font-size:11px; color:#666; line-height:1.6; }

    /* Items */
    .items-table { width:100%; border-collapse:collapse; margin-bottom:16px; }
    .items-table thead tr { background:#696cff; color:#fff; }
    .items-table thead th { padding:8px 10px; font-size:11px; font-weight:bold; text-transform:uppercase; }
    .items-table tbody tr { border-bottom:1px solid #eee; }
    .items-table tbody tr:nth-child(even) { background:#f8f8ff; }
    .items-table tbody td { padding:8px 10px; }
    .tr { text-align:right; }
    .tc { text-align:center; }

    /* Totals */
    .totals-wrap { width:100%; }
    .totals-inner { width:44%; float:right; border-collapse:collapse; }
    .totals-inner td { padding:5px 10px; font-size:12px; }
    .totals-inner .lbl { color:#666; }
    .totals-inner .val { text-align:right; font-weight:600; color:#333; }
    .totals-inner .t-total td { border-top:2px solid #696cff; padding-top:8px; font-size:14px; font-weight:bold; color:#696cff; }
    .totals-inner .t-balance td { color:#dc3545; font-size:13px; font-weight:bold; }
    .totals-inner .t-paid td { color:#28a745; }
    .clearfix { clear:both; }

    /* Footer */
    .footer { margin-top:30px; border-top:2px solid #696cff; padding-top:14px; }
    .footer-inner { width:100%; border-collapse:collapse; }
    .footer-inner td { vertical-align:top; padding:0 6px; }
    .bank-meta { font-size:11px; color:#555; line-height:1.8; }
    .bank-meta b { color:#333; display:block; }

    .thank-you { text-align:center; margin-top:22px; font-size:12px; color:#888; font-style:italic; }
</style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width:55%; vertical-align:top;">
                <div class="biz-name">{{ $biz->name }}</div>
                <div class="biz-meta">
                    @if($biz->address){{ $biz->address }}@if($biz->city), {{ $biz->city }}@endif<br>@endif
                    @if($biz->phone)📞 {{ $biz->phone }}@if($biz->email) &nbsp;|&nbsp; ✉ {{ $biz->email }}@endif<br>@endif
                    @if($biz->manager_name)Manager: {{ $biz->manager_name }}@endif
                </div>
            </td>
            <td style="width:45%; vertical-align:top;">
                <div class="inv-title">{{ $isPurchase ? 'Purchase Receipt' : 'Invoice' }}</div>
                <div class="inv-meta">
                    <b># {{ $transaction->bill_number }}</b><br>
                    Date: <b>{{ $transaction->date->format('d M Y') }}</b><br>
                    Type: {{ $transaction->type->label() }}
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- Items --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:4%;" class="tc">#</th>
                <th style="text-align:left;">Description</th>
                @if(!$isDairy)<th style="width:8%;" class="tc">Unit</th>@endif
                <th style="width:12%;" class="tr">Qty{{ $isDairy ? ' (L)' : '' }}</th>
                @if($isDairy)<th style="width:9%;" class="tr">Fat %</th>@endif
                <th style="width:13%;" class="tr">Rate</th>
                <th style="width:13%;" class="tr">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $i => $item)
            <tr>
                <td class="tc">{{ $i + 1 }}</td>
                <td>{{ $item->description }}</td>
                @if(!$isDairy)<td class="tc">{{ $item->unit ?? '—' }}</td>@endif
                <td class="tr">{{ number_format($item->qty, 3) }}</td>
                @if($isDairy)<td class="tr">{{ $item->fat !== null ? number_format($item->fat,2).'%' : '—' }}</td>@endif
                <td class="tr">Rs.{{ number_format($item->rate, 2) }}</td>
                <td class="tr">Rs.{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals-inner">
        <tr>
            <td class="lbl">Subtotal</td>
            <td class="val">Rs.{{ number_format($transaction->subtotal, 2) }}</td>
        </tr>
        @if($transaction->discount > 0)
        <tr>
            <td class="lbl">Discount</td>
            <td class="val" style="color:#dc3545;">- Rs.{{ number_format($transaction->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="t-total">
            <td class="lbl">Total</td>
            <td class="val">Rs.{{ number_format($transaction->total_amount) }}</td>
        </tr>
        <tr class="t-paid">
            <td class="lbl">{{ $isPurchase ? 'Paid' : 'Received' }}</td>
            <td class="val">Rs.{{ number_format($transaction->amount_received) }}</td>
        </tr>
        @if($transaction->balance > 0)
        <tr class="t-balance">
            <td class="lbl">{{ $isPurchase ? 'Pending to Pay' : 'Balance Due' }}</td>
            <td class="val">Rs.{{ number_format($transaction->balance) }}</td>
        </tr>
        @else
        <tr>
            <td colspan="2" class="val" style="color:#28a745; text-align:right;">✔ Fully Settled</td>
        </tr>
        @endif
    </table>
    <div class="clearfix"></div>

    {{-- Footer: Bill To (left) + Bank Details (right) --}}
    <div class="footer">
        <table class="footer-inner">
            <tr>
                {{-- LEFT: Bill To --}}
                <td style="width:45%; padding-right:20px;">
                    <div class="footer-label">{{ $isPurchase ? 'Vendor / Farmer' : 'Bill To' }}</div>
                    <div class="party-name">{{ $party?->name ?? '—' }}</div>
                    <div class="party-meta">
                        @if($party?->mobile)📞 {{ $party->mobile }}<br>@endif
                        @if($party?->address){{ $party->address }}<br>@endif
                    </div>
                    @if($transaction->notes)
                        <div style="margin-top:8px;">
                            <div class="footer-label">Notes</div>
                            <div class="party-meta">{{ $transaction->notes }}</div>
                        </div>
                    @endif
                </td>

                {{-- RIGHT: Bank Details --}}
                @if($biz->bank_account_number)
                <td style="width:55%; border-left:1px solid #eee; padding-left:20px;">
                    <div class="footer-label">Bank Details</div>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            @if($biz->bank_name)
                            <td class="bank-meta" style="width:25%;"><b>Bank</b>{{ $biz->bank_name }}</td>
                            @endif
                            @if($biz->bank_holder_name)
                            <td class="bank-meta" style="width:30%;"><b>Account Name</b>{{ $biz->bank_holder_name }}</td>
                            @endif
                            <td class="bank-meta" style="width:25%;"><b>Account No.</b>{{ $biz->bank_account_number }}</td>
                            @if($biz->bank_ifsc_code)
                            <td class="bank-meta" style="width:20%;"><b>IFSC</b>{{ $biz->bank_ifsc_code }}</td>
                            @endif
                        </tr>
                    </table>
                </td>
                @else
                <td></td>
                @endif
            </tr>
        </table>
    </div>

    <div class="thank-you">Thank you for your business! 🙏</div>

</div>
</body>
</html>