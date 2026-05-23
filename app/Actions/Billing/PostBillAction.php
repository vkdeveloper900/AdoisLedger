<?php

namespace App\Actions\Billing;

use App\Enums\TransactionType;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PostBillAction
{
    public function execute(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            $lastBalance = LedgerEntry::where('business_profile_id', $transaction->business_profile_id)
                ->where('customer_id', $transaction->customer_id)
                ->latest('id')
                ->value('running_balance') ?? 0;

            $date  = $transaction->date->toDateString();
            $bizId = $transaction->business_profile_id;
            $custId = $transaction->customer_id;
            $txId  = $transaction->id;
            $total = $transaction->total_amount;
            $paid  = $transaction->amount_received;
            $bill  = "Bill #{$transaction->bill_number}";

            $isDairyPurchase = $transaction->type === TransactionType::DairyPurchase;

            $entries = [];

            if ($isDairyPurchase) {
                // We bought milk from vendor → we owe them → balance goes negative (we owe)
                $balanceAfter = $lastBalance - $total;
                $entries[] = $this->entry($txId, $bizId, $custId, 'payable', 0, $total, $balanceAfter, $date, $bill);

                if ($paid > 0) {
                    $balanceAfter += $paid;
                    $entries[] = $this->entry($txId, $bizId, $custId, 'payment_made', $paid, 0, $balanceAfter, $date, "Payment on {$bill}");
                }
            } else {
                // We sold to customer → they owe us → balance goes positive
                $balanceAfter = $lastBalance + $total;
                $entries[] = $this->entry($txId, $bizId, $custId, 'receivable', $total, 0, $balanceAfter, $date, $bill);

                if ($paid > 0) {
                    $balanceAfter -= $paid;
                    $entries[] = $this->entry($txId, $bizId, $custId, 'payment_received', 0, $paid, $balanceAfter, $date, "Payment on {$bill}");
                }
            }

            LedgerEntry::insert($entries);

            $transaction->update([
                'status'    => 'posted',
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);
        });
    }

    private function entry(int $txId, int $bizId, ?int $custId, string $head, int $debit, int $credit, int $balance, string $date, string $desc): array
    {
        return [
            'transaction_id'      => $txId,
            'business_profile_id' => $bizId,
            'customer_id'         => $custId,
            'account_head'        => $head,
            'debit'               => $debit,
            'credit'              => $credit,
            'running_balance'     => $balance,
            'date'                => $date,
            'description'         => $desc,
            'created_at'          => now(),
            'updated_at'          => now(),
        ];
    }
}
