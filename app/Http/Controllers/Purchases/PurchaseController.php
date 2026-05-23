<?php

namespace App\Http\Controllers\Purchases;

use App\Actions\Billing\PostBillAction;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\StorePurchaseRequest;
use App\Models\BusinessProfile;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $bizId = session('active_business_id');

        $query = Transaction::with('customer')
            ->where('business_profile_id', $bizId)
            ->where('type', TransactionType::DairyPurchase->value)
            ->latest('date');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $purchases = $query->paginate(20)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $bizId    = session('active_business_id');
        $business = BusinessProfile::findOrFail($bizId);
        $vendors  = $this->vendorsForBusiness($bizId);

        return view('purchases.create', compact('business', 'vendors'));
    }

    public function store(StorePurchaseRequest $request, PostBillAction $postBill)
    {
        $bizId    = session('active_business_id');
        $business = BusinessProfile::findOrFail($bizId);

        DB::transaction(function () use ($request, $business, $postBill) {
            $subtotal = collect($request->items)
                ->sum(fn($i) => round((float)$i['qty'] * (float)$i['rate'], 2));

            $discount = (float) ($request->discount ?? 0);
            $total    = (int) floor($subtotal - $discount);
            $paid     = (int) $request->amount_paid;

            // Auto-assign vendor to business if not already
            $vendor = Customer::findOrFail($request->vendor_id);
            $vendor->businessProfiles()->syncWithoutDetaching([$business->id]);

            $transaction = Transaction::create([
                'business_profile_id' => $business->id,
                'customer_id'         => $vendor->id,
                'created_by'          => auth()->id(),
                'type'                => TransactionType::DairyPurchase->value,
                'bill_number'         => $this->nextPurchaseNumber($business->id),
                'date'                => $request->date,
                'status'              => 'draft',
                'subtotal'            => $subtotal,
                'discount'            => $discount,
                'total_amount'        => $total,
                'amount_received'     => $paid,
                'balance'             => $total - $paid,
                'notes'               => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $transaction->items()->create([
                    'description' => $item['description'],
                    'unit'        => 'liter',
                    'qty'         => $item['qty'],
                    'fat'         => $item['fat'] ?? null,
                    'rate'        => $item['rate'],
                    'amount'      => round((float)$item['qty'] * (float)$item['rate'], 2),
                ]);
            }

            $postBill->execute($transaction);
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase recorded and posted.');
    }

    public function show(Transaction $purchase)
    {
        $purchase->load('items', 'customer', 'business');
        return view('purchases.show', compact('purchase'));
    }

    public function pdf(Transaction $purchase)
    {
        $purchase->load('items', 'customer', 'business');
        $pdf = Pdf::loadView('pdf.invoice', ['transaction' => $purchase])
                  ->setPaper('a4', 'portrait');
        return $pdf->stream($purchase->bill_number . '.pdf');
    }

    public function vendorInfo(Request $request)
    {
        $bizId  = session('active_business_id');
        $vendor = Customer::findOrFail($request->vendor_id);

        $balance = LedgerEntry::where('business_profile_id', $bizId)
            ->where('customer_id', $vendor->id)
            ->latest('id')->value('running_balance') ?? 0;

        $lastPurchases = Transaction::where('business_profile_id', $bizId)
            ->where('customer_id', $vendor->id)
            ->where('type', TransactionType::DairyPurchase->value)
            ->latest('date')->latest('id')
            ->limit(3)
            ->get(['id', 'bill_number', 'date', 'total_amount', 'amount_received', 'balance']);

        return response()->json([
            'balance'        => $balance,
            'last_purchases' => $lastPurchases,
        ]);
    }

    // ── Helpers ──

    private function vendorsForBusiness(int $bizId): array
    {
        $inBiz  = Customer::whereIn('party_type', ['vendor', 'both'])
                    ->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $bizId))
                    ->orderBy('name')->get();
        $others = Customer::whereIn('party_type', ['vendor', 'both'])
                    ->whereDoesntHave('businessProfiles', fn($q) => $q->where('business_profiles.id', $bizId))
                    ->orderBy('name')->get();

        return ['in_business' => $inBiz, 'others' => $others];
    }

    private function nextPurchaseNumber(int $bizId): string
    {
        $last = Transaction::where('business_profile_id', $bizId)
            ->where('type', TransactionType::DairyPurchase->value)
            ->max('id') ?? 0;
        return 'PUR-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }
}
