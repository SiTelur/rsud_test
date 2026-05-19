<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderSummaryResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $cacheKey = 'dashboard_summary';
        $isCached = Cache::has($cacheKey);

        $data = Cache::remember($cacheKey, 300, function () {
            $totalRevenueOrderComplete = Order::where('status', 'complete')->sum('total_price');
            $totalOrderToday = Order::whereDate('created_at', now()->toDateString())->count();
            $totalProductActive = Product::where('is_active', true)->count();
            $lowStockCount = Product::where('stock', '<', 5)->count();

            $topProductOrders = Order::selectRaw('product_id, SUM(qty) as total_qty')
                ->where('status', 'complete')
                ->groupBy('product_id')
                ->with('product.category')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();

            $topProducts = $topProductOrders->map(function ($order) {
                return [
                    'product_id' => $order->product_id,
                    'total_qty' => (int) $order->total_qty,
                    'product' => $order->product ? [
                        'id' => $order->product->id,
                        'name' => $order->product->name,
                        'category_name' => $order->product->category?->name,
                    ] : null,
                ];
            });

            $latestOrders = Order::with(['user', 'product.category'])
                ->latest()
                ->limit(10)
                ->get();

            return [
                'total_revenue_ordercomplete' => $totalRevenueOrderComplete,
                'total_order_today' => $totalOrderToday,
                'total_product_active' => $totalProductActive,
                'low_stock_count' => $lowStockCount,
                'top_products' => $topProducts,
                'latest_orders' => OrderSummaryResource::collection($latestOrders)->resolve(),
            ];
        });

        $data['from_cache'] = $isCached;

        return response()->json($data);
    }
}
