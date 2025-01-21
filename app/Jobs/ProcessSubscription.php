<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessSubscription implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        $payload = [
            'ProductName' => $this->subscription['name'],
            'Price' => $this->subscription['price'],
            'Timestamp' => now(),
        ];

        // Send the subscription to the third-party endpoint
        $response = Http::post(env('THIRD_PARTY_ENDPOINT'), $payload);

        if (!$response->successful()) {
            $this->release(10); // Retry after 10 seconds
        }
    }
}
