<?php

namespace Tests\Unit;

use App\Jobs\PayItemSyncJob;
use App\Models\Business;
use App\Models\PayItem;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PayItemSyncJobTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
       
        $this->user = User::factory()->create(['external_id' => 'user_' . uniqid()]);
        $this->business = Business::factory()->create(['external_id' => 'business_' . uniqid(), 'enabled' => true]);
        $this->user->businesses()->attach($this->business);
    }

    protected function tearDown(): void
    {
        User::truncate();
        Business::truncate();
        PayItem::truncate();

        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_syncs_pay_items_successfully()
    {
        Http::fake([
            'https://some-partner-website.com/*' => Http::response([
                'payItems' => [
                    [
                        'id' => 'PAYITEM123',
                        'employeeId' => $this->user->external_id,
                        'hoursWorked' => 40,
                        'payRate' => 15,
                        'date' => '2024-09-05',
                    ],
                ],
                'isLastPage' => true,
            ], 200),
        ]);

        PayItemSyncJob::dispatch($this->business);

        $this->assertNotNull(
            PayItem::where('external_id', 'PAYITEM123')
                ->where('hours_worked', 40)
                ->where('pay_rate', 15)
                ->where('pay_date', '2024-09-05')
                ->first()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_unauthorized_response()
    {
        Http::fake([
            'https://some-partner-website.com/*' => Http::response([], 401),
        ]);

        PayItemSyncJob::dispatch($this->business);

        $this->assertNull(
            PayItem::where('business_id', $this->business->_id)->first()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_not_found_response()
    {
        Http::fake([
            'https://some-partner-website.com/*' => Http::response([], 404),
        ]);

        PayItemSyncJob::dispatch($this->business);

        $this->assertNull(
            PayItem::where('business_id', $this->business->_id)->first()
        );
    }
}
