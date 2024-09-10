<?php

namespace App\Jobs;

use App\Models\Business;
use App\Models\User;
use App\Models\PayItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayItemSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $business;

    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const API_KEY = 'CLAIR-ABC-123';
    const API_BASE_URL = 'https://some-partner-website.com/clair-pay-item-sync';

    /**
     * Create a new job instance.
     *
     * @param Business $business
     */
    public function __construct(Business $business)
    {
        $this->business = $business;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $page = 1;
        $business = $this->business;

        do {
            $response = Http::withHeaders([
                'x-api-key' => self::API_KEY,
            ])->get(self::API_BASE_URL . "/{$business->external_id}?page={$page}");

            if ($response->status() == self::STATUS_UNAUTHORIZED) {
                Log::alert("401 Unauthorized for business {$business->external_id}");
                $this->fail();
                return;
            }

            if ($response->status() == self::STATUS_NOT_FOUND) {
                Log::critical("404 Business Not Found: {$business->external_id}");
                $this->fail();
                return;
            }

            $payItems = $response->json('payItems');
            foreach ($payItems as $payItemData) {
                $user = User::where('external_id', $payItemData['employeeId'])
                    ->whereHas('businesses', fn($q) => $q->where('_id', $business->_id))
                    ->first();

                if (!$user) {
                    continue;
                }

                $payItem = PayItem::firstOrNew([
                    'external_id' => $payItemData['id'],
                    'user_id' => $user->_id,
                    'business_id' => $business->_id,
                ]);

                $deduction = $business->deduction_percentage ?? 30;

                $payItem->fill([
                    'hours_worked' => $payItemData['hoursWorked'],
                    'pay_rate' => $payItemData['payRate'],
                    'pay_date' => $payItemData['date'],
                    'amount' => round($payItemData['hoursWorked'] * $payItemData['payRate'] * ($deduction / 100), 2, PHP_ROUND_HALF_UP),
                ]);

                $payItem->save();
            }

            $page++;
        } while (!$response->json('isLastPage'));

        PayItem::where('business_id', $business->_id)
            ->whereNotIn('external_id', collect($payItems)->pluck('id'))
            ->delete();
    }
}
