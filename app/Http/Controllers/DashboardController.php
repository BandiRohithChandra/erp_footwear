<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\Payroll;
use App\Models\Sale;
use App\Models\Employee;
use App\Models\Client;
use App\Models\RawMaterial;
use App\Models\LiquidMaterial;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // -----------------------------
        // 💰 Finance Summary
        // -----------------------------
        $totalIncome = DB::table('invoices')->sum('amount');

        // ✅ Payroll Summary (From employee_batch)
        $payrollSummary = DB::table('employee_batch')
            ->selectRaw('
            SUM(quantity * labor_rate) AS total_salary,
            SUM(paid_amount) AS total_paid,
            SUM(advance_amount) AS total_advance
        ')
            ->first();

        $totalSalary = $payrollSummary->total_salary ?? 0;
        $totalPaid = $payrollSummary->total_paid ?? 0;
        $totalAdvance = $payrollSummary->total_advance ?? 0;
        $totalRemaining = $totalSalary - ($totalPaid + $totalAdvance);

        // ✅ Finance: Expenses include actual paid payrolls + claims
        $totalExpenseClaims = DB::table('expense_claims')->sum('amount');
        $totalPayrollAmount = $totalPaid;
        $totalExpenses = $totalPayrollAmount + $totalExpenseClaims;

        // -----------------------------
        // 👥 HR Summary
        // -----------------------------
        $totalEmployees = Employee::count();
        $totalClients = \App\Models\User::whereIn('category', ['wholesale', 'retail'])->count();

        // -----------------------------
        // 🏭 Production KPIs
        // -----------------------------
        $totalProcesses = DB::table('production_processes')->count();
        $activeProcesses = DB::table('production_processes')->where('status', 'in_progress')->count();
        $completedProcesses = DB::table('production_processes')->where('status', 'completed')->count();
        $pendingProcesses = DB::table('production_processes')->where('status', 'pending')->count();

        $overdueOrders = DB::table('production_orders')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        $totalOutput = DB::table('production_processes')->sum('completed_quantity');
        $totalAssigned = DB::table('production_processes')->sum('assigned_quantity');
        $efficiency = $totalAssigned > 0 ? round(($totalOutput / $totalAssigned) * 100, 2) : 0;

        // -----------------------------
        // 📦 Production Summary (Stage-Wise)
        // -----------------------------
        $totalProductionOrders = ProductionOrder::count();
        $totalPendingOrders = ProductionOrder::where('status', 'pending')->count();
        $totalCompletedOrders = ProductionOrder::where('status', 'completed')->count();
        $totalDelayedOrders = ProductionOrder::where('status', 'delayed')->count();

        $stageSummary = DB::table('production_processes')
            ->select('stage', 'status', DB::raw('COUNT(*) as total'))
            ->groupBy('stage', 'status')
            ->get()
            ->groupBy('stage');

        $stages = $stageSummary->keys();
        $statuses = ['pending', 'in_progress', 'completed', 'paid'];

        $stageData = collect($statuses)->mapWithKeys(function ($status) use ($stageSummary) {
            return [$status => $stageSummary->map(fn($s) => $s->where('status', $status)->sum('total'))->values()];
        });

        // -----------------------------
        // 📊 Product Completion Rate
        // -----------------------------
        $productProgress = DB::table('production_processes')
            ->join('products', 'production_processes.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(assigned_quantity) as total_assigned'),
                DB::raw('SUM(completed_quantity) as total_completed')
            )
            ->groupBy('products.id', 'products.name')
            ->get()
            ->map(function ($p) {
                $p->completion_rate = $p->total_assigned > 0
                    ? round(($p->total_completed / $p->total_assigned) * 100, 2)
                    : 0;
                return $p;
            });

        // -----------------------------
        // 🧱 Low Stock Alerts
        // -----------------------------
        $lowRawMaterials = RawMaterial::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('stocks')
                ->whereColumn('stocks.item_id', 'raw_materials.id')
                ->where('qty_available', '<', 50)
                ->where('type', 'raw');
        })->get();

        $lowLiquidMaterials = LiquidMaterial::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('stocks')
                ->whereColumn('stocks.item_id', 'liquid_materials.id')
                ->where('qty_available', '<', 20);
        })->get();

        $lowSoles = DB::table('stocks')
            ->where('type', 'sole')
            ->where('qty_available', '<', 10)
            ->join('products', 'stocks.item_id', '=', 'products.id')
            ->select('products.name', 'stocks.qty_available as quantity')
            ->get();

        // -----------------------------
        // 🏅 Top Employees (Payroll)
        // -----------------------------
        $topEmployees = DB::table('employee_batch')
            ->join('employees', 'employee_batch.employee_id', '=', 'employees.id')
            ->select('employees.name', DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('employees.id', 'employees.name')
            ->orderByDesc('total_paid')
            ->limit(5)
            ->get();

        // -----------------------------
        // 💹 Income vs Expenses Chart
        // -----------------------------
        $transactionDates = collect(
            DB::table('invoices')->selectRaw('DATE(created_at) as date')->pluck('date')
        )
            ->merge(DB::table('worker_payrolls')->selectRaw('DATE(created_at) as date')->pluck('date'))
            ->merge(DB::table('expense_claims')->selectRaw('DATE(created_at) as date')->pluck('date'))
            ->unique()
            ->sort()
            ->values();

        $incomeData = $transactionDates->map(
            fn($date) =>
            DB::table('invoices')
                ->whereDate('created_at', $date)
                ->sum(DB::raw('COALESCE(amount_paid, amount, 0)'))
        );

        $expenseData = $transactionDates->map(function ($date) {
            $payroll = DB::table('worker_payrolls')
                ->whereDate('created_at', $date)
                ->sum(DB::raw('COALESCE(total_amount, amount, 0)'));
            $claims = DB::table('expense_claims')
                ->whereDate('created_at', $date)
                ->sum('amount');
            return $payroll + $claims;
        });

        // -----------------------------
        // 📈 Payroll Trends (Worker Payrolls)
        // -----------------------------
        $payrollDates = DB::table('worker_payrolls')
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date');

        $payrollData = $payrollDates->map(
            fn($date) =>
            DB::table('worker_payrolls')
                ->whereDate('created_at', $date)
                ->sum(DB::raw('COALESCE(total_amount, amount, 0)'))
        );

        // -----------------------------
        // 🛒 Top Products (Sales)
        // -----------------------------
        $topProducts = Sale::selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'product_id');

        $topProducts = $topProducts->mapWithKeys(function ($value, $key) {
            $product = DB::table('products')->where('id', $key)->first();
            return [$product->name ?? "Product $key" => $value];
        });

        // -----------------------------
        // 👷 Employee Performance
        // -----------------------------
        $employeePerformance = DB::table('employee_batch')
            ->join('employees', 'employee_batch.employee_id', '=', 'employees.id')
            ->select('employees.name', DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('employees.id', 'employees.name')
            ->orderByDesc('total_paid')
            ->limit(5)
            ->pluck('total_paid', 'name');

        // -----------------------------
        // 📦 Material Usage (Placeholder)
        // -----------------------------
        $materialUsageData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            'usage' => [120, 90, 150, 80, 130]
        ];

        // -----------------------------
        // 🕒 Recent Activities
        // -----------------------------
        $recentActivities = collect()
            ->merge(
                ProductionOrder::latest()->limit(3)->get()->map(fn($o) => [
                    'type' => 'Production',
                    'description' => "Production Order #{$o->id} - Status: {$o->status}",
                    'created_at' => $o->created_at,
                ])
            )
            ->merge(
                Employee::latest()->limit(3)->get()->map(fn($e) => [
                    'type' => 'Employee',
                    'description' => "New Employee: {$e->name}",
                    'created_at' => $e->created_at,
                ])
            )
            ->merge(
                Sale::latest()->limit(3)->get()->map(fn($s) => [
                    'type' => 'Sale',
                    'description' => "Sale ID #{$s->id} - ₹{$s->amount}",
                    'created_at' => $s->created_at,
                ])
            )
            ->sortByDesc('created_at')
            ->take(6)
            ->values();

        // -----------------------------
        // ✅ Return to Dashboard
        // -----------------------------
        // Get recent production orders for order list
        $productionOrders = ProductionOrder::with(['product', 'client'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            // Finance
            'totalIncome',
            'totalExpenses',
            'totalPayrollAmount',
            'totalExpenseClaims',
            'totalSalary',
            'totalPaid',
            'totalAdvance',
            'totalRemaining',

            // HR
            'totalEmployees',
            'totalClients',

            // Production
            'totalProductionOrders',
            'totalPendingOrders',
            'totalCompletedOrders',
            'totalDelayedOrders',
            'totalProcesses',
            'activeProcesses',
            'completedProcesses',
            'pendingProcesses',
            'overdueOrders',
            'totalOutput',
            'totalAssigned',
            'efficiency',

            // Analytics
            'stages',
            'stageData',
            'productProgress',
            'transactionDates',
            'incomeData',
            'expenseData',
            'payrollDates',
            'payrollData',
            'topProducts',
            'employeePerformance',
            'recentActivities',
            'topEmployees',
            'lowRawMaterials',
            'lowLiquidMaterials',
            'lowSoles',
            'materialUsageData',

            // Orders
            'productionOrders'
        ));
    }

    public function edit()
    {
        $user = auth()->user();

        // Standard cards with default labels
        $standardCards = [
            'finance_summary' => 'Finance Summary',
            'hr_summary' => 'HR Summary',
            'production_kpis' => 'Production KPIs',
            'low_stock_alerts' => 'Low Stock Alerts',
            'charts_section' => 'Charts Section',
        ];

        // Get user selected cards, default to standard cards if empty
        $userCards = $user->dashboard_cards;
        if (is_string($userCards)) {
            $userCards = json_decode($userCards, true) ?? array_keys($standardCards);
        } else {
            $userCards = $userCards ?? array_keys($standardCards);
        }

        // Get custom labels
        $customLabels = $user->custom_card_labels;
        if (is_string($customLabels)) {
            $customLabels = json_decode($customLabels, true) ?? [];
        } else {
            $customLabels = $customLabels ?? [];
        }

        // Only include user-added custom cards (not in standard)
        $userAddedCustomCards = [];
        foreach ($userCards as $key) {
            if (!isset($standardCards[$key])) {
                $label = isset($customLabels[$key]['label']) ? $customLabels[$key]['label'] : ($customLabels[$key] ?? ucwords(str_replace(['_', '-'], ' ', $key)));
                $userAddedCustomCards[$key] = $label;
            }
        }

        return view('dashboard_edit', compact(
            'standardCards',
            'userAddedCustomCards',
            'userCards',
            'customLabels'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $standardCards = [
            'finance_summary',
            'hr_summary',
            'production_kpis',
            'low_stock_alerts',
            'charts_section',
        ];

        // Validate request
        $request->validate([
            'cards' => 'array',
            'custom_labels' => 'array',
            'custom_labels.*.label' => 'nullable|string|max:255',
            'custom_labels.*.url' => 'nullable|url|max:255',
            'custom_labels.*.color' => 'nullable|in:gray,blue,teal,indigo,purple,rose,orange,lime,cyan,slate,fuchsia',
            'new_custom_key' => 'nullable|string|max:255',
            'new_custom_label' => 'nullable|string|max:255',
            'new_custom_url' => 'nullable|url|max:255',
            'new_custom_color' => 'nullable|in:gray,blue,teal,indigo,purple,rose,orange,lime,cyan,slate,fuchsia',
        ]);

        // Get selected cards from request
        $selectedCards = $request->input('cards', []);

        // Process custom labels only for selected cards
        $customLabels = $request->input('custom_labels', []);
        $filteredCustomLabels = [];

        foreach ($customLabels as $key => $data) {
            $keySlug = \Illuminate\Support\Str::slug($key, '_');
            if (in_array($keySlug, $selectedCards)) {
                $filteredCustomLabels[$keySlug] = [
                    'label' => $data['label'] ?? ucwords(str_replace(['_', '-'], ' ', $keySlug)),
                    'url' => $data['url'] ?? null,
                    'color' => $data['color'] ?? 'gray',
                ];
            }
        }

        // Add new custom card if filled
        if ($request->filled('new_custom_key') && $request->filled('new_custom_label')) {
            $newKey = \Illuminate\Support\Str::slug($request->new_custom_key, '_');
            if (!in_array($newKey, $selectedCards) && !in_array($newKey, $standardCards)) {
                $selectedCards[] = $newKey;
                $filteredCustomLabels[$newKey] = [
                    'label' => $request->new_custom_label,
                    'url' => $request->new_custom_url ?? null,
                    'color' => $request->new_custom_color ?? 'gray',
                ];
            }
        }

        // Remove duplicates and reindex
        $selectedCards = array_values(array_unique($selectedCards));

        // Save to user
        $user->dashboard_cards = json_encode($selectedCards);
        $user->custom_card_labels = json_encode($filteredCustomLabels);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Dashboard updated successfully!');
    }


}