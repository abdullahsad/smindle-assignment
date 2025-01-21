<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_validates_order_payload()
    {
        $response = $this->postJson('/api/v1/orders', [
            'last_name' => 'Turing',
            'address' => '123 Enigma Ave, Bletchley Park, UK',
            'basket' => [
                [
                    'name' => 'Smindle ElePHPant plushie',
                    'type' => 'unit',
                    'price' => 295.45,
                ],
                [
                    'name' => 'Syntax & Chill',
                    'type' => 'subscription',
                    'price' => 175.00,
                ],
            ],
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_saves_valid_orders_to_database()
    {
        $payload = [
            'first_name' => 'Alan test',
            'last_name' => 'Turing test',
            'address' => '123 Enigma Ave, Bletchley Park, UK',
            'basket' => [
                [
                    'name' => 'Smindle ElePHPant plushie',
                    'type' => 'unit',
                    'price' => 295.45,
                ],
                [
                    'name' => 'Syntax & Chill',
                    'type' => 'subscription',
                    'price' => 175.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['first_name' => 'Alan test']);
    }

}

