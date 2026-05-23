<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Transaction;

class BalanceSheetController extends Controller
{
    public function index()
    {
        $bizId = session('active_business_id');

        $rows = Customer::whereHas('ledgerEntries', fn($q) => $q->where('business_profile_id', $bizId))
            ->withSum(['ledgerEntries as total_debit'  => fn($q) => $q->where('business_profile_id', $bizId)], 'debit')
            ->withSum(['ledgerEntries as total_credit' => fn($q) => $q->where('business_profile_id', $bizId)], 'credit')
            ->orderBy('name')
            ->get();

        foreach ($rows as $c) {
            $c->balance = LedgerEntry::where('business_profile_id', $bizId)
                ->where('customer_id', $c->id)
                ->latest('id')->value('running_balance') ?? 0;
        }

        // Positive balance = customer owes us (receivable)
        // Negative balance = we owe vendor (payable)
        $receivables = $rows->filter(fn($c) => $c->balance >= 0);
        $payables    = $rows->filter(fn($c) => $c->balance < 0);

        return view('reports.balance-sheet', compact('receivables', 'payables'));
    }

    public function show(Customer $customer)
    {
        $bizId = session('active_business_id');

        $entries = LedgerEntry::where('business_profile_id', $bizId)
            ->where('customer_id', $customer->id)
            ->with('transaction')
            ->orderBy('date')->orderBy('id')
            ->get();

        abort_if($entries->isEmpty(), 404);

        $balance = $entries->last()->running_balance ?? 0;

        return view('reports.balance-sheet-customer', compact('customer', 'entries', 'balance'));
    }

    public function billDetail(Transaction $transaction)
    {
        $bizId = session('active_business_id');
        abort_if($transaction->business_profile_id !== $bizId, 403);

        $transaction->load('items', 'customer');

        return view('reports.partials.bill-offcanvas-body', compact('transaction'));
    }
}
