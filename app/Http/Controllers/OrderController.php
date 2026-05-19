<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderSummaryResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $user = $request->user();
        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->qty;

        if ($product->stock < $qty) {
            return response()->json(['message' => 'Stok produk tidak mencukupi'], 422);
        }

        $order = null;

        DB::transaction(function () use ($user, $product, $qty, &$order) {
            // Lock the product row to avoid race conditions
            $p = Product::where('id', '=', $product->id, 'and')->lockForUpdate()->first();

            if ($p->stock < $qty) {
                throw new HttpResponseException(
                    response()->json(['message' => 'Stok produk tidak mencukupi'], 422)
                );
            }

            $total = (int) round($p->price * $qty);

            $order = Order::create([
                'user_id' => $user->id,
                'product_id' => $p->id,
                'qty' => $qty,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            $p->decrement('stock', $qty);
        });

        return response()->json([
            'message' => 'Order berhasil dibuat',
            'data' => $order,
        ], 201);
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->user_id !== $user->id) {
            return response()->json([
                'message' => 'Order bukan milik Anda.',
            ], 404);
        }

        return response()->json([
            'message' => 'Order ditemukan.',
            'data' => new OrderSummaryResource($order->load(['user', 'product.category'])),
        ]);
    }
}
