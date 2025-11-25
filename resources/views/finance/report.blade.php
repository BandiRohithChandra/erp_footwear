<!DOCTYPE html>
<html>
<head>
    <title>Financial Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        h2 { font-size: 16px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Financial Report</h1>
    <p>Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
    <p>Default Currency: {{ $defaultCurrency }}</p>

    <h2>Summary</h2>
    <p><strong>Accounts Payable:</strong> {{ $formattedTotalPayable }}</p>
    <p><strong>Accounts Receivable:</strong> {{ $formattedTotalReceivable }}</p>

    <h2>Recent Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ $transaction->formatted_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Forecasted Payables (Next 6 Months)</h2>
    <table>
        <thead>
            <tr>
                @foreach($futureMonths as $month)
                    <th>{{ $month }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($forecastedPayable as $amount)
                    <td>{{ $amount }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <h2>Forecasted Receivables (Next 6 Months)</h2>
    <table>
        <thead>
            <tr>
                @foreach($futureMonths as $month)
                    <th>{{ $month }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($forecastedReceivable as $amount)
                    <td>{{ $amount }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
</html>