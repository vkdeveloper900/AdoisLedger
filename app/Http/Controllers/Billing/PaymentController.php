<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $bizId = session('active_business_id');
        $mode  = $request->input('mode', 'received'); // received | made

        if ($mode === 'made') {
            $parties = Customer::whereIn('party_type', ['vendor', 'both'])
                ->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $bizId))
                ->orderBy('name')->get();
        } else {
            $parties = Customer::whereIn('party_type', ['customer', 'both'])
                ->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $bizId))
                ->orderBy('name')->get();
        }

        $selectedCustomer = $request->input('customer_id');

        return view('billing.payment', compact('parties', 'selectedCustomer', 'mode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => ['required', 'exists:customers,id'],
            'amount'         => ['required', 'integer', 'min:1'],
            'date'           => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank,upi'],
            'mode'           => ['required', 'in:received,made'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $bizId = session('active_business_id');
        $isVendorPayment = $request->mode === 'made';

        DB::transaction(function () use ($request, $bizId, $isVendorPayment) {
            Payment::create([
                'business_profile_id' => $bizId,
                'customer_id'         => $request->customer_id,
                'created_by'          => auth()->id(),
                'payment_type'        => $request->mode,
                'amount'              => $request->amount,
                'date'                => $request->date,
                'payment_method'      => $request->payment_method,
                'notes'               => $request->notes,
            ]);

            $lastBalance = LedgerEntry::where('business_profile_id', $bizId)
                ->where('customer_id', $request->customer_id)
                ->latest('id')->value('running_balance') ?? 0;

            if ($isVendorPayment) {
                // We pay vendor: debit side, running_balance increases (less negative)
                LedgerEntry::create([
                    'transaction_id'      => null,
                    'business_profile_id' => $bizId,
                    'customer_id'         => $request->customer_id,
                    'account_head'        => 'payment_made',
                    'debit'               => $request->amount,
                    'credit'              => 0,
                    'running_balance'     => $lastBalance + $request->amount,
                    'date'                => $request->date,
                    'description'         => 'Payment made to vendor · ' . ucfirst($request->payment_method),
                ]);
            } else {
                // Customer pays us: credit side, running_balance decreases
                LedgerEntry::create([
                    'transaction_id'      => null,
                    'business_profile_id' => $bizId,
                    'customer_id'         => $request->customer_id,
                    'account_head'        => 'payment_received',
                    'debit'               => 0,
                    'credit'              => $request->amount,
                    'running_balance'     => $lastBalance - $request->amount,
                    'date'                => $request->date,
                    'description'         => 'Payment received · ' . ucfirst($request->payment_method),
                ]);
            }

            // Update open transaction balances
            $remaining = $request->amount;
            Transaction::where('business_profile_id', $bizId)
                ->where('customer_id', $request->customer_id)
                ->where('balance', '>', 0)
                ->orderBy('date')
                ->each(function ($tx) use (&$remaining) {
                    if ($remaining <= 0) return false;
                    $apply = min($remaining, $tx->balance);
                    $tx->decrement('balance', $apply);
                    $tx->increment('amount_received', $apply);
                    $remaining -= $apply;
                });
        });

        return redirect()->route('ledger.show', $request->customer_id)
            ->with('success', $isVendorPayment ? 'Payment to vendor recorded.' : 'Payment received.');
    }

    public function ledger(Customer $customer)
    {
        $bizId   = session('active_business_id');
        $entries = LedgerEntry::where('business_profile_id', $bizId)
            ->where('customer_id', $customer->id)
            ->orderBy('date')->orderBy('id')
            ->get();

        $totalBilled   = $entries->sum('debit');
        $totalReceived = $entries->sum('credit');
        $balance       = $entries->last()?->running_balance ?? 0;
        $business      = \App\Models\BusinessProfile::find($bizId);

        return view('billing.ledger', compact('customer', 'entries', 'totalBilled', 'totalReceived', 'balance', 'business'));
    }
}
