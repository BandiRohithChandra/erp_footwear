<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;

class SalesCommissionController extends Controller
{
    // Method to get sales reps with commission
    public function index()
    {
        $salesReps = DB::table('employees as e')
            ->leftJoin('sales_commissions as sc', 'e.id', '=', 'sc.employee_id')
            ->select(
                'e.id',
                'e.name as sales_rep',
                'e.salary as base_salary',
                DB::raw('IFNULL(SUM(sc.commission_amount), 0) as total_commission'),
                DB::raw('(e.salary + IFNULL(SUM(sc.commission_amount), 0)) as total_payout')
            )
            ->where('e.employee_type', 'sales')
            ->groupBy('e.id', 'e.name', 'e.salary')
            ->orderBy('e.name')
            ->get();

        return view('sales_commissions.index', compact('salesReps'));
    }
}
