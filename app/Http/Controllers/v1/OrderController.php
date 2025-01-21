<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderRequest;
use App\Jobs\ProcessSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Save order to the database
            $order = Order::create($request->validated());

            if (!$order) {
                return response()->json(['message' => 'Failed to create order'], 400);
            }

            // Prepare order items for bulk insertion
            $orderItems = [];
            $subscriptions = [];

            foreach ($request->basket as $item) {
                // Validate each basket item
                if (!isset($item['name'], $item['type'], $item['price'])) {
                    throw new \Exception('Invalid basket item data');
                }

                $orderItems[] = [
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'price' => $item['price'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                // Collect subscription items for later processing
                if ($item['type'] === 'subscription') {
                    $subscriptions[] = $item;
                }
            }

            // Bulk insert order items
            OrderItem::insert($orderItems);

            // Commit the transaction
            DB::commit();

            // Dispatch subscription jobs outside of the transaction
            foreach ($subscriptions as $subscription) {
                dispatch(new ProcessSubscription($subscription));
            }

            return response()->json(['message' => 'Order placed successfully'], 201);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            // Log the exception for debugging
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'request' => $request->all(),
            ]);

            return response()->json(['message' => 'Failed to place order'], 500);
        }
    }

}
