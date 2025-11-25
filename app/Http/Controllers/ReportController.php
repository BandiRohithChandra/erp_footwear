<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.sales', compact('sales', 'startDate', 'endDate'));
    }

    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['ID', 'Client', 'Total Amount', 'Date']);
        foreach ($sales as $sale) {
            $csv->insertOne([
                $sale->id,
                $sale->client->name ?? 'N/A',
                $sale->total_amount,
                $sale->created_at->format('Y-m-d'),
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales_report.csv"',
        ]);
    }

    public function inventoryReport(Request $request)
    {
        $products = Product::with('warehouse')->get();
        return view('reports.inventory', compact('products'));
    }

    public function exportInventoryReport()
    {
        $products = Product::with('warehouse')->get();

        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['ID', 'Name', 'Quantity', 'Warehouse']);
        foreach ($products as $product) {
            $csv->insertOne([
                $product->id,
                $product->name,
                $product->quantity,
                $product->warehouse->name ?? 'N/A',
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory_report.csv"',
        ]);
    }

    public function financeReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.finance', compact('transactions', 'startDate', 'endDate'));
    }

    public function exportFinanceReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['ID', 'Description', 'Type', 'Amount', 'Date']);
        foreach ($transactions as $transaction) {
            $csv->insertOne([
                $transaction->id,
                $transaction->description,
                $transaction->type,
                $transaction->total_amount,
                $transaction->created_at->format('Y-m-d'),
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="finance_report.csv"',
        ]);
    }

    public function employeePerformanceReport(Request $request)
    {
        $employees = Employee::with('performanceReviews')->get();
        return view('reports.employee-performance', compact('employees'));
    }

    public function exportEmployeePerformanceReport()
    {
        $employees = Employee::with('performanceReviews')->get();

        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['ID', 'Name', 'Average Rating']);
        foreach ($employees as $employee) {
            $averageRating = $employee->performanceReviews->avg('rating') ?? 'N/A';
            $csv->insertOne([
                $employee->id,
                $employee->name,
                $averageRating,
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employee_performance_report.csv"',
        ]);
    }

    public function payrollReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->input('status', 'disbursed');

        $payrolls = Payroll::with(['employee', 'manager', 'financeApprover'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', $status)
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalAmount = $payrolls->sum('amount');
        $totalTaxAmount = $payrolls->sum('tax_amount');
        $totalPayable = $payrolls->sum('total_amount');

        return view('reports.payroll', compact('payrolls', 'startDate', 'endDate', 'status', 'totalAmount', 'totalTaxAmount', 'totalPayable'));
    }

    public function exportPayrollReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->input('status', 'disbursed');

        $payrolls = Payroll::with(['employee', 'manager', 'financeApprover'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', $status)
            ->orderBy('payment_date', 'desc')
            ->get();

        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['Employee', 'Amount', 'Tax Amount', 'Total Amount', 'Payment Date', 'Status', 'Manager', 'Finance Approver', 'Disbursed At']);
        foreach ($payrolls as $payroll) {
            $csv->insertOne([
                $payroll->employee->name,
                \App\Helpers\FormatMoney::format($payroll->amount, $payroll->region),
                \App\Helpers\FormatMoney::format($payroll->tax_amount, $payroll->region),
                \App\Helpers\FormatMoney::format($payroll->total_amount, $payroll->region),
                $payroll->payment_date->format('Y-m-d'),
                $payroll->status,
                $payroll->manager ? $payroll->manager->name : 'N/A',
                $payroll->financeApprover ? $payroll->financeApprover->name : 'N/A',
                $payroll->disbursed_at ? $payroll->disbursed_at->format('Y-m-d H:i:s') : 'N/A',
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payroll_report.csv"',
        ]);
    }
}