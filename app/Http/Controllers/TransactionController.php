<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Notifications\TransactionApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this import

class TransactionController extends Controller
{
    use AuthorizesRequests; // Add this trait

    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');

        $transactions = Transaction::query()
            ->with('approvedBy')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%")
                             ->orWhere('category', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage finance');

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'tax_rate_select' => 'required|in:0,5,15,18,20,custom',
            'tax_rate' => 'required_if:tax_rate_select,custom|nullable|numeric|min:0|max:100',
            'transaction_date' => 'required|date',
        ]);

        $taxRate = $validated['tax_rate_select'] === 'custom' ? $validated['tax_rate'] : $validated['tax_rate_select'];
        $taxAmount = $validated['amount'] * ($taxRate / 100);
        $totalAmount = $validated['amount'] + $taxAmount;

        $transaction = Transaction::create([
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'transaction_date' => $validated['transaction_date'],
            'status' => 'pending',
            'region' => config('taxes.default_region', 'in'), // Default region if not specified
        ]);

        // Notify users with 'approve transactions' permission
        $approvers = \App\Models\User::permission('approve transactions')->get();
        Notification::send($approvers, new TransactionApprovalNotification($transaction));

        return redirect()->route('transactions.index')->with('success', __('Transaction added successfully and is pending approval!'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('manage finance');
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('manage finance');

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'tax_rate_select' => 'required|in:0,5,15,18,20,custom',
            'tax_rate' => 'required_if:tax_rate_select,custom|nullable|numeric|min:0|max:100',
            'transaction_date' => 'required|date',
        ]);

        $taxRate = $validated['tax_rate_select'] === 'custom' ? $validated['tax_rate'] : $validated['tax_rate_select'];
        $taxAmount = $validated['amount'] * ($taxRate / 100);
        $totalAmount = $validated['amount'] + $taxAmount;

        $transaction->update([
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'transaction_date' => $validated['transaction_date'],
            'status' => 'pending', // Reset status to pending on update
            'approved_by' => null,
            'approved_at' => null,
            'region' => config('taxes.default_region', 'in'), // Default region
        ]);

        // Notify users with 'approve transactions' permission
        $approvers = \App\Models\User::permission('approve transactions')->get();
        Notification::send($approvers, new TransactionApprovalNotification($transaction));

        return redirect()->route('transactions.index')->with('success', __('Transaction updated successfully and is pending approval!'));
    }


public function fetchItems(Request $request)
{
    $category = $request->category;

    switch ($category) {

        case 'invoice_payment':
    $items = \App\Models\Invoice::where('status', 'pending')
        ->get(['id', 'po_no', 'amount'])
        ->map(function ($inv) {
            $label = "Invoice #{$inv->id} | Amount ₹{$inv->amount}";
            if ($inv->po_no) {
                $label .= " | PO: {$inv->po_no}";
            } else {
                $label .= " | PO: N/A";
            }
            return [
                'id' => $inv->id,
                'label' => $label
            ];
        });
    break;


        case 'purchase_order':
            $items = \App\Models\SupplierOrder::where('payment_status', 'pending')
                ->get(['id', 'po_number', 'total_amount'])
                ->map(function ($po) {
                    return [
                        'id' => $po->id,
                        'label' => 'PO ' . $po->po_number . ' | ₹' . $po->total_amount
                    ];
                });
            break;

        case 'worker_payroll':
            $items = \App\Models\WorkerPayroll::where('status', 'paid')
                ->get(['id', 'employee_id', 'amount', 'payment_date'])
                ->map(function ($wp) {
                    return [
                        'id' => $wp->id,
                        'label' => "Payroll #{$wp->id} | ₹{$wp->amount} | Date: {$wp->payment_date}"
                    ];
                });
            break;

        case 'salary_advance':
            $items = \App\Models\SalaryAdvance::where('status', 'Pending')
                ->get(['id', 'employee_id', 'amount', 'date'])
                ->map(function ($sa) {
                    return [
                        'id' => $sa->id,
                        'label' => "Advance #{$sa->id} | ₹{$sa->amount} | Date: {$sa->date}"
                    ];
                });
            break;

        case 'expense_claim':
            $items = \App\Models\ExpenseClaim::where('status', 'pending')
                ->get(['id', 'employee_id', 'amount', 'expense_date'])
                ->map(function ($ec) {
                    return [
                        'id' => $ec->id,
                        'label' => "Claim #{$ec->id} | ₹{$ec->amount} | Date: {$ec->expense_date}"
                    ];
                });
            break;

        default:
            $items = collect();
    }

    return response()->json(['items' => $items->values()]);
}


    public function destroy(Transaction $transaction)
    {
        $this->authorize('manage finance');
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', __('Transaction deleted successfully!'));
    }

    public function approve(Request $request, Transaction $transaction)
    {
        $this->authorize('approve transactions');

        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')->with('error', __('Transaction cannot be approved as it is not pending.'));
        }

        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('transactions.index')->with('success', __('Transaction approved successfully!'));
    }

    public function reject(Request $request, Transaction $transaction)
    {
        $this->authorize('approve transactions');

        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')->with('error', __('Transaction cannot be rejected as it is not pending.'));
        }

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('transactions.index')->with('success', __('Transaction rejected successfully!'));
    }

    public function export()
    {
        $transactions = Transaction::with('approvedBy')->get();
        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['Description', 'Type', 'Category', 'Amount', 'Tax Rate', 'Tax Amount', 'Total Amount', 'Date', 'Status', 'Approved By', 'Approved At']);
        foreach ($transactions as $transaction) {
            $csv->insertOne([
                $transaction->description,
                $transaction->type,
                $transaction->category ?? 'N/A',
                \App\Helpers\FormatMoney::format($transaction->amount, $transaction->region),
                $transaction->tax_rate ? $transaction->tax_rate . '%' : 'N/A',
                \App\Helpers\FormatMoney::format($transaction->tax_amount, $transaction->region),
                \App\Helpers\FormatMoney::format($transaction->total_amount, $transaction->region),
                $transaction->transaction_date,
                $transaction->status,
                $transaction->approvedBy ? $transaction->approvedBy->name : 'N/A',
                $transaction->approved_at ? $transaction->approved_at->format('Y-m-d H:i:s') : 'N/A',
            ]);
        }
        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $this->authorize('manage finance');

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        Transaction::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('transactions.index')->with('success', __('Selected transactions deleted successfully!'));
    }
}