<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Settings;
use App\Helpers\FormatMoney;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\FinancialReportMail;
use App\Notifications\TransactionApprovalNotification;
use Illuminate\Support\Facades\Notification;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $defaultCurrency = Settings::get('default_currency', 'SAR');
        // Update type to 'expense' and use total_amount; include pending and approved statuses
        $totalPayable = Transaction::where('type', 'expense')
            ->whereIn('status', ['pending', 'approved'])
            ->sum('total_amount');
        // Update type to 'income' and use total_amount; include pending and approved statuses
        $totalReceivable = Transaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved'])
            ->sum('total_amount');
        $formattedTotalPayable = FormatMoney::format($totalPayable);
        $formattedTotalReceivable = FormatMoney::format($totalReceivable);
        $recentTransactions = Transaction::whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $transaction->formatted_amount = FormatMoney::format($transaction->total_amount);
                return $transaction;
            });

        $monthlyData = Transaction::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, type, SUM(total_amount) as total_amount')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->whereIn('status', ['pending', 'approved'])
            ->groupBy('year', 'month', 'type')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $payableData = [];
        $receivableData = [];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = $date->format('M Y');
            $payableData[$key] = 0;
            $receivableData[$key] = 0;
        }

        foreach ($monthlyData as $data) {
            $key = Carbon::create($data->year, $data->month)->format('Y-m');
            if ($data->type === 'expense') {
                $payableData[$key] = $data->total_amount;
            } elseif ($data->type === 'income') {
                $receivableData[$key] = $data->total_amount;
            }
        }

        $payableDataValues = array_values($payableData);
        $receivableDataValues = array_values($receivableData);

        $payableForecastLR = $this->forecastLinearRegression($payableDataValues, 6);
        $receivableForecastLR = $this->forecastLinearRegression($receivableDataValues, 6);
        $payableForecastWMA = $this->forecastWeightedMovingAverage($payableDataValues, 6);
        $receivableForecastWMA = $this->forecastWeightedMovingAverage($receivableDataValues, 6);

        $payableForecast = [];
        $receivableForecast = [];
        for ($i = 0; $i < 6; $i++) {
            $payableForecast[] = ($payableForecastLR[$i] + $payableForecastWMA[$i]) / 2;
            $receivableForecast[] = ($receivableForecastLR[$i] + $receivableForecastWMA[$i]) / 2;
        }

        $forecastedPayable = array_map([FormatMoney::class, 'format'], $payableForecast);
        $forecastedReceivable = array_map([FormatMoney::class, 'format'], $receivableForecast);

        $futureMonths = [];
        for ($i = 1; $i <= 6; $i++) {
            $futureMonths[] = Carbon::now()->addMonths($i)->format('M Y');
        }

        $chartPayableData = array_merge($payableDataValues, $payableForecast);
        $chartReceivableData = array_merge($receivableDataValues, $receivableForecast);
        $chartLabels = array_merge($months, $futureMonths);

        return view('finance.dashboard', compact(
            'formattedTotalPayable',
            'formattedTotalReceivable',
            'recentTransactions',
            'defaultCurrency',
            'payableData',
            'receivableData',
            'months',
            'forecastedPayable',
            'forecastedReceivable',
            'futureMonths',
            'chartLabels',
            'chartPayableData',
            'chartReceivableData'
        ));
    }

    private function forecastLinearRegression($data, $futurePeriods)
    {
        $n = count($data);
        if ($n < 2) {
            return array_fill(0, $futurePeriods, 0);
        }

        $xSum = 0;
        $ySum = 0;
        $xySum = 0;
        $xxSum = 0;

        for ($i = 0; $i < $n; $i++) {
            $xSum += $i;
            $ySum += $data[$i];
            $xySum += $i * $data[$i];
            $xxSum += $i * $i;
        }

        $m = ($n * $xySum - $xSum * $ySum) / ($n * $xxSum - $xSum * $xSum);
        $b = ($ySum - $m * $xSum) / $n;

        $forecast = [];
        for ($i = $n; $i < $n + $futurePeriods; $i++) {
            $forecast[] = max(0, $m * $i + $b);
        }

        return $forecast;
    }

    private function forecastWeightedMovingAverage($data, $futurePeriods, $window = 3)
    {
        $n = count($data);
        if ($n < $window) {
            return array_fill(0, $futurePeriods, 0);
        }

        $weights = range(1, $window);
        $weightSum = array_sum($weights);

        $forecast = [];
        $lastValues = array_slice($data, -$window);

        for ($i = 0; $i < $futurePeriods; $i++) {
            $wma = 0;
            for ($j = 0; $j < $window; $j++) {
                $wma += $lastValues[$j] * $weights[$j];
            }
            $wma = $wma / $weightSum;
            $forecast[] = max(0, $wma);
            $lastValues[] = $wma;
            $lastValues = array_slice($lastValues, -($window));
        }

        return $forecast;
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with('approvedBy')->orderBy('created_at', 'desc');

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $transactions = $query->paginate(10);

        return view('finance.transactions', compact('transactions'));
    }

    public function approveTransaction(Request $request, Transaction $transaction)
    {
        $this->authorize('approve transactions');

        $transaction->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('finance.transactions')
            ->with('success', 'Transaction approved successfully.');
    }

    public function rejectTransaction(Request $request, Transaction $transaction)
    {
        $this->authorize('approve transactions');

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('finance.transactions')
            ->with('success', 'Transaction rejected successfully.');
    }

    public function createTransaction(Request $request)
    {
        $this->authorize('create transactions');

        $validated = $request->validate([
            'type' => 'required|in:payable,receivable',
            'category' => 'required|in:salary,purchase,other',
            'amount' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::create($validated);

        // Notify users with 'approve transactions' permission
        $approvers = \App\Models\User::permission('approve transactions')->get();
        Notification::send($approvers, new TransactionApprovalNotification($transaction));

        return redirect()->route('finance.transactions')
            ->with('success', 'Transaction created successfully and is pending approval.');
    }

    public function generateReport()
    {
        $defaultCurrency = Settings::get('default_currency', 'SAR');
        $totalPayable = Transaction::where('type', 'expense')
            ->whereIn('status', ['pending', 'approved'])
            ->sum('total_amount');
        $totalReceivable = Transaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved'])
            ->sum('total_amount');
        $formattedTotalPayable = FormatMoney::format($totalPayable);
        $formattedTotalReceivable = FormatMoney::format($totalReceivable);
        $recentTransactions = Transaction::whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $transaction->formatted_amount = FormatMoney::format($transaction->total_amount);
                return $transaction;
            });

        $monthlyData = Transaction::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, type, SUM(total_amount) as total_amount')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->whereIn('status', ['pending', 'approved'])
            ->groupBy('year', 'month', 'type')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $payableData = [];
        $receivableData = [];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = $date->format('M Y');
            $payableData[$key] = 0;
            $receivableData[$key] = 0;
        }

        foreach ($monthlyData as $data) {
            $key = Carbon::create($data->year, $data->month)->format('Y-m');
            if ($data->type === 'expense') {
                $payableData[$key] = $data->total_amount;
            } elseif ($data->type === 'income') {
                $receivableData[$key] = $data->total_amount;
            }
        }

        $payableDataValues = array_values($payableData);
        $receivableDataValues = array_values($receivableData);

        $payableForecastLR = $this->forecastLinearRegression($payableDataValues, 6);
        $receivableForecastLR = $this->forecastLinearRegression($receivableDataValues, 6);
        $payableForecastWMA = $this->forecastWeightedMovingAverage($payableDataValues, 6);
        $receivableForecastWMA = $this->forecastWeightedMovingAverage($receivableDataValues, 6);

        $payableForecast = [];
        $receivableForecast = [];
        for ($i = 0; $i < 6; $i++) {
            $payableForecast[] = ($payableForecastLR[$i] + $payableForecastWMA[$i]) / 2;
            $receivableForecast[] = ($receivableForecastLR[$i] + $receivableForecastWMA[$i]) / 2;
        }

        $forecastedPayable = array_map([FormatMoney::class, 'format'], $payableForecast);
        $forecastedReceivable = array_map([FormatMoney::class, 'format'], $receivableForecast);

        $futureMonths = [];
        for ($i = 1; $i <= 6; $i++) {
            $futureMonths[] = Carbon::now()->addMonths($i)->format('M Y');
        }

        $pdf = PDF::loadView('finance.report', compact(
            'formattedTotalPayable',
            'formattedTotalReceivable',
            'recentTransactions',
            'defaultCurrency',
            'payableData',
            'receivableData',
            'months',
            'forecastedPayable',
            'forecastedReceivable',
            'futureMonths'
        ));

        return $pdf->download('financial_report_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    public function exportTransactions()
    {
        $transactions = Transaction::all();
        $filename = 'transactions_' . Carbon::now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Category', 'Amount', 'Status', 'Approved By', 'Approved At', 'Date']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->type,
                    $transaction->category,
                    $transaction->total_amount,
                    $transaction->status,
                    $transaction->approvedBy ? $transaction->approvedBy->name : 'N/A',
                    $transaction->approved_at ? $transaction->approved_at->format('Y-m-d H:i:s') : 'N/A',
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function generateScheduledReport()
    {
        $defaultCurrency = Settings::get('default_currency', 'SAR');
        $totalPayable = Transaction::where('type', 'expense')
            ->whereIn('status', ['pending', 'approved'])
            ->sum('total_amount');
        $totalReceivable = Transaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved'])
            ->sum('total_amount');
        $formattedTotalPayable = FormatMoney::format($totalPayable);
        $formattedTotalReceivable = FormatMoney::format($totalReceivable);
        $recentTransactions = Transaction::whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $transaction->formatted_amount = FormatMoney::format($transaction->total_amount);
                return $transaction;
            });

        $monthlyData = Transaction::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, type, SUM(total_amount) as total_amount')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->whereIn('status', ['pending', 'approved'])
            ->groupBy('year', 'month', 'type')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $payableData = [];
        $receivableData = [];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = $date->format('M Y');
            $payableData[$key] = 0;
            $receivableData[$key] = 0;
        }

        foreach ($monthlyData as $data) {
            $key = Carbon::create($data->year, $data->month)->format('Y-m');
            if ($data->type === 'expense') {
                $payableData[$key] = $data->total_amount;
            } elseif ($data->type === 'income') {
                $receivableData[$key] = $data->total_amount;
            }
        }

        $payableDataValues = array_values($payableData);
        $receivableDataValues = array_values($receivableData);

        $payableForecastLR = $this->forecastLinearRegression($payableDataValues, 6);
        $receivableForecastLR = $this->forecastLinearRegression($receivableDataValues, 6);
        $payableForecastWMA = $this->forecastWeightedMovingAverage($payableDataValues, 6);
        $receivableForecastWMA = $this->forecastWeightedMovingAverage($receivableDataValues, 6);

        $payableForecast = [];
        $receivableForecast = [];
        for ($i = 0; $i < 6; $i++) {
            $payableForecast[] = ($payableForecastLR[$i] + $payableForecastWMA[$i]) / 2;
            $receivableForecast[] = ($receivableForecastLR[$i] + $receivableForecastWMA[$i]) / 2;
        }

        $forecastedPayable = array_map([FormatMoney::class, 'format'], $payableForecast);
        $forecastedReceivable = array_map([FormatMoney::class, 'format'], $receivableForecast);

        $futureMonths = [];
        for ($i = 1; $i <= 6; $i++) {
            $futureMonths[] = Carbon::now()->addMonths($i)->format('M Y');
        }

        $pdf = PDF::loadView('finance.report', compact(
            'formattedTotalPayable',
            'formattedTotalReceivable',
            'recentTransactions',
            'defaultCurrency',
            'payableData',
            'receivableData',
            'months',
            'forecastedPayable',
            'forecastedReceivable',
            'futureMonths'
        ));

        $pdfPath = storage_path('app/public/financial_report_' . Carbon::now()->format('Ymd') . '.pdf');
        $pdf->save($pdfPath);

        $adminEmail = config('mail.admin_email', 'admin@example.com');
        Mail::to($adminEmail)->send(new FinancialReportMail($pdfPath));

        return true;
    }
}