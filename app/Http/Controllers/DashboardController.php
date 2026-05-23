<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\BusinessProfile;
use App\Models\Customer;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $bizId   = session('active_business_id');
        $profile = $bizId ? BusinessProfile::find($bizId) : null;

        if ($profile) {
            $data = $this->businessStats($profile);
        } else {
            $data = $this->overallStats();
        }

        return view('dashboard.index', array_merge($data, ['profiles' => BusinessProfile::all()]));
    }

private function businessStats(BusinessProfile $profile): array
    {
        $id    = $profile->id;
        $isDairy = $profile->business_type_id === 2;

        $saleTypes = $isDairy
            ? [TransactionType::DairySale->value]
            : ($profile->business_type_id === 3
                ? [TransactionType::Construction->value]
                : [TransactionType::GeneralSale->value]);

        // ── Stat cards ──
        $totalSalesMonth = Transaction::where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_amount');

        $totalSalesAllTime = Transaction::where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->sum('total_amount');

        $totalReceivable = Transaction::where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->sum('balance');

        $paymentsReceivedMonth = Transaction::where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount_received');

        $customersCount = Customer::whereIn('party_type', ['customer', 'both'])
            ->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $id))
            ->count();

        $totalPurchasesMonth = 0;
        $totalPayable        = 0;
        $vendorsCount        = 0;

        if ($isDairy) {
            $totalPurchasesMonth = Transaction::where('business_profile_id', $id)
                ->where('type', TransactionType::DairyPurchase->value)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('total_amount');

            $totalPayable = Transaction::where('business_profile_id', $id)
                ->where('type', TransactionType::DairyPurchase->value)
                ->sum('balance');

            $vendorsCount = Customer::whereIn('party_type', ['vendor', 'both'])
                ->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $id))
                ->count();
        }

        // ── Monthly revenue last 6 months ──
        $monthlyRevenue = Transaction::where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->where('date', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(total_amount) as total")
            ->groupByRaw("DATE_FORMAT(date, '%Y-%m')")
            ->orderBy('month')
            ->pluck('total', 'month');

        // ── Daily sales last 30 days ──
        $dailySales = Transaction::where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->where('date', '>=', now()->subDays(29)->toDateString())
            ->selectRaw('date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // ── Recent transactions ──
        $recentTransactions = Transaction::with('customer')
            ->where('business_profile_id', $id)
            ->latest('date')->latest('id')
            ->limit(10)
            ->get();

        // ── Top outstanding customers ──
        $topOutstanding = Transaction::with('customer')
            ->where('business_profile_id', $id)
            ->whereIn('type', $saleTypes)
            ->where('balance', '>', 0)
            ->selectRaw('customer_id, SUM(balance) as total_balance')
            ->groupBy('customer_id')
            ->orderByDesc('total_balance')
            ->limit(5)
            ->get();

        return compact(
            'profile', 'isDairy',
            'totalSalesMonth', 'totalSalesAllTime', 'totalReceivable',
            'paymentsReceivedMonth', 'customersCount',
            'totalPurchasesMonth', 'totalPayable', 'vendorsCount',
            'monthlyRevenue', 'dailySales',
            'recentTransactions', 'topOutstanding'
        );
    }

    private function overallStats(): array
    {
        $profile  = null;
        $isDairy  = false;

        $totalSalesMonth = Transaction::whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_amount');

        $totalSalesAllTime = Transaction::whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])->sum('total_amount');

        $totalReceivable = Transaction::whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])->sum('balance');

        $paymentsReceivedMonth = Transaction::whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount_received');

        $totalPurchasesMonth = Transaction::where('type', TransactionType::DairyPurchase->value)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_amount');

        $totalPayable = Transaction::where('type', TransactionType::DairyPurchase->value)
            ->sum('balance');

        $customersCount = Customer::whereIn('party_type', ['customer', 'both'])->count();
        $vendorsCount   = Customer::whereIn('party_type', ['vendor', 'both'])->count();

        $monthlyRevenue = Transaction::whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])
            ->where('date', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(total_amount) as total")
            ->groupByRaw("DATE_FORMAT(date, '%Y-%m')")
            ->orderBy('month')
            ->pluck('total', 'month');

        $dailySales = Transaction::whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])
            ->where('date', '>=', now()->subDays(29)->toDateString())
            ->selectRaw('date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $recentTransactions = Transaction::with('customer', 'business')
            ->latest('date')->latest('id')
            ->limit(10)
            ->get();

        $topOutstanding = Transaction::with('customer')
            ->whereIn('type', [
                TransactionType::DairySale->value,
                TransactionType::GeneralSale->value,
                TransactionType::Construction->value,
            ])
            ->where('balance', '>', 0)
            ->selectRaw('customer_id, SUM(balance) as total_balance')
            ->groupBy('customer_id')
            ->orderByDesc('total_balance')
            ->limit(5)
            ->get();

        return compact(
            'profile', 'isDairy',
            'totalSalesMonth', 'totalSalesAllTime', 'totalReceivable',
            'paymentsReceivedMonth', 'customersCount',
            'totalPurchasesMonth', 'totalPayable', 'vendorsCount',
            'monthlyRevenue', 'dailySales',
            'recentTransactions', 'topOutstanding'
        );
    }
}
