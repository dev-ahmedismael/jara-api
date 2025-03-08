<?php

namespace App\Http\Controllers\Central\Stats;

use App\Http\Controllers\Controller;
use App\Models\Central\Customer\Customer;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{

    public function index() {
        $total_sales = 0;
         $total_customers = Customer::count();

        $salesPerMonth = [];

        $customersPerMonth = Customer::select(
            DB::raw("COUNT(id) as total_customers"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();



        return response()->json([
            'total_sales' => $total_sales,
             'total_customers' => $total_customers,
             'sales_per_month' => $salesPerMonth,
            'customers_per_month' => $customersPerMonth,
         ], 200);
    }


}
