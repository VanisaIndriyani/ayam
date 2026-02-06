<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // total revenue (paid / completed)
        $totalRevenue = Order::whereIn('payment_status', ['paid'])
            ->sum('grand_total');

        // total orders
        $totalOrders = Order::count();

        // total customers (user yang pernah order)
        $totalCustomers = User::whereHas('orders')->count();

        // average order value
        $averageOrderValue = $totalOrders > 0
            ? round($totalRevenue / $totalOrders)
            : 0;

        // today stats
        $days = 30;

        $today = Carbon::today();

        $todayOrders = Order::whereDate('ordered_at', $today)->count();
        $todayRevenue = Order::whereDate('ordered_at', $today)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $processingOrders = Order::whereIn('status', ['processing', 'packed', 'shipped'])->count();

        // status breakdown
        $statusSummary = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // recent orders
        $recentOrders = Order::with('user')
            ->latest('ordered_at')
            ->take(10)
            ->get();
        // ============================
    // CHART: REVENUE 30 HARI
    // ============================
    $revenueChart = Order::where('payment_status', 'paid')
        ->where('ordered_at', '>=', now()->subDays($days))
        ->selectRaw('DATE(ordered_at) as date, SUM(grand_total) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Format data untuk chart
    $chartDates = $revenueChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
    $chartRevenue = $revenueChart->pluck('total');

    // ============================
    // PRODUK TERLARIS (TOP 5)
    // ============================
    $topProducts = \App\Models\OrderItem::selectRaw('product_id, product_name, SUM(quantity) as total_sold')
        ->groupBy('product_id', 'product_name')
        ->orderByDesc('total_sold')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'averageOrderValue',
            'todayOrders',
            'todayRevenue',
            'pendingOrders',
            'processingOrders',
            'statusSummary',
            'recentOrders',
            'chartDates',
            'chartRevenue',
            'topProducts',
        ));
    }
}
