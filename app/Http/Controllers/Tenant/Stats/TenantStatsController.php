<?php

namespace App\Http\Controllers\Tenant\Stats;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Consultation\Consultation;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Order\Order;
use Illuminate\Support\Facades\DB;

class TenantStatsController extends Controller
{
    public function index() {
        $total_sales = Order::sum('paid_amount');
        $total_orders = Order::count();
        $total_customers = Customer::count();
        $total_consultations = Consultation::count();

        $salesPerMonth = Order::select(
            DB::raw("SUM(paid_amount) as total_sales"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $customersPerMonth = Customer::select(
            DB::raw("COUNT(id) as total_customers"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $ordersPerMonth = Order::select(
            DB::raw("COUNT(id) as total_orders"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $consultationsPerMonth = Consultation::select(
            DB::raw("COUNT(id) as total_consultations"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'total_sales' => $total_sales,
            'total_orders' => $total_orders,
            'total_customers' => $total_customers,
            'total_consultations' => $total_consultations,
            'sales_per_month' => $salesPerMonth,
            'customers_per_month' => $customersPerMonth,
            'consultations_per_month' => $consultationsPerMonth,
            'orders_per_month' => $ordersPerMonth,
        ], 200);
    }
}
