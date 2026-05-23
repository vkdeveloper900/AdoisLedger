<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\PostBillAction;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\StoreBillRequest;
use App\Models\BusinessProfile;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Material;
use App\Models\Transaction;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $bizId = session('active_business_id');

        $query = Transaction::with('customer')
            ->where('business_profile_id', $bizId)
            ->where('type', '!=', TransactionType::DairyPurchase->value)
            ->latest('date');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $bills = $query->paginate(20)->withQueryString();

        return view('billing.index', compact('bills'));
    }

    public function create(Request $request)
    {
        $bizId    = session('active_business_id');
        $business = BusinessProfile::findOrFail($bizId);
        $type     = $request->input('type', $this->defaultType($business->business_type_id));
        $customers = $this->customersForBusiness($bizId);

        // Construction needs materials & units from DB
        if ($business->business_type_id === 3) {
            $materials = Material::where('business_profile_id', $bizId)->where('is_active', true)->orderBy('name')->pluck('name');
            $units     = Unit::where('business_profile_id', $bizId)->where('is_active', true)->orderBy('name')->pluck('name');
            return view('billing.create-construction', compact('business', 'type', 'customers', 'materials', 'units'));
        }

        if ($business->business_type_id === 2) {
            return view('billing.create-dairy', compact('business', 'type', 'customers'));
        }

        return view('billing.create-general', compact('business', 'type', 'customers'));
    }

    public function store(StoreBillRequest $request, PostBillAction $postBill)
    {
        $bizId    = session('active_business_id');
        $business = BusinessProfile::findOrFail($bizId);

        DB::transaction(function () use ($request, $business, $postBill) {
            $subtotal = collect($request->items)
                ->sum(fn($i) => round((float)$i['qty'] * (float)$i['rate'], 2));

            $discount = (float) ($request->discount ?? 0);
            $total    = (int) floor($subtotal - $discount);
            $received = (int) $request->amount_received;

            // Auto-assign customer to business if not already
            $customer = Customer::findOrFail($request->customer_id);
            $customer->businessProfiles()->syncWithoutDetaching([$business->id]);

            $transaction = Transaction::create([
                'business_profile_id' => $business->id,
                'customer_id'         => $customer->id,
                'created_by'          => auth()->id(),
                'type'                => $request->type,
                'bill_number'         => $this->nextBillNumber($business->id),
                'date'                => $request->date,
                'status'              => 'draft',
                'subtotal'            => $subtotal,
                'discount'            => $discount,
                'total_amount'        => $total,
                'amount_received'     => $received,
                'balance'             => $total - $received,
                'notes'               => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $transaction->items()->create([
                    'description' => $item['description'],
                    'unit'        => $item['unit'] ?? null,
                    'qty'         => $item['qty'],
                    'fat'         => $item['fat'] ?? null,
                    'rate'        => $item['rate'],
                    'amount'      => round((float)$item['qty'] * (float)$item['rate'], 2),
                ]);
            }

            $postBill->execute($transaction);
        });

        return redirect()->route('bills.index')
            ->with('success', 'Bill created and posted.');
    }

    public function customerInfo(Request $request)
    {
        $bizId    = session('active_business_id');
        $customer = Customer::findOrFail($request->customer_id);

        $balance = LedgerEntry::where('business_profile_id', $bizId)
            ->where('customer_id', $customer->id)
            ->latest('id')->value('running_balance') ?? 0;

        $lastTransactions = Transaction::where('business_profile_id', $bizId)
            ->where('customer_id', $customer->id)
            ->latest('date')->latest('id')
            ->limit(3)
            ->get(['id', 'bill_number', 'date', 'total_amount', 'amount_received', 'balance']);

        return response()->json([
            'balance'          => $balance,
            'last_transactions' => $lastTransactions,
        ]);
    }

    public function show(Transaction $bill)
    {
        $bill->load('items', 'customer', 'business');
        return view('billing.show', compact('bill'));
    }

    public function pdf(Transaction $bill)
    {
        $bill->load('items', 'customer', 'business');
        $pdf = Pdf::loadView('pdf.invoice', ['transaction' => $bill])
                  ->setPaper('a4', 'portrait');
        return $pdf->stream($bill->bill_number . '.pdf');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function defaultType(int $businessTypeId): string
    {
        return match($businessTypeId) {
            2 => TransactionType::DairySale->value,
            3 => TransactionType::Construction->value,
            default => TransactionType::GeneralSale->value,
        };
    }

    private function customersForBusiness(int $bizId): array
    {
        // Group 1: already in this business, Group 2: others
        $inBiz  = Customer::whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $bizId))
                    ->orderBy('name')->get();
        $others = Customer::whereDoesntHave('businessProfiles', fn($q) => $q->where('business_profiles.id', $bizId))
                    ->orderBy('name')->get();

        return ['in_business' => $inBiz, 'others' => $others];
    }

    private function nextBillNumber(int $bizId): string
    {
        $last = Transaction::where('business_profile_id', $bizId)->max('id') ?? 0;
        return 'BILL-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }
}
