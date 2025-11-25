<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Warning Letter') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .details p { margin: 5px 0; }
        .signature { margin-top: 50px; }
        .print-button { text-align: center; margin-top: 20px; }
        @media print {
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Warning Letter') }}</h1>
        <p>{{ __('Issue Date') }}: {{ $warningLetter->issue_date ?? $warningLetter->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="details">
        <p><strong>{{ __('Employee') }}:</strong> {{ $warningLetter->employee && $warningLetter->employee->user ? $warningLetter->employee->user->name : 'Employee Not Found' }}</p>
        <p><strong>{{ __('Reason') }}:</strong> {{ $warningLetter->reason }}</p>
        <p><strong>{{ __('Description') }}:</strong> {{ $warningLetter->description ?? 'No additional description provided.' }}</p>
        <p><strong>{{ __('Issued By') }}:</strong> {{ $warningLetter->issuer ? $warningLetter->issuer->name : 'HR Department' }}</p>
        <p><strong>{{ __('Status') }}:</strong> {{ $warningLetter->status }}</p>
        @if ($warningLetter->status === 'uploaded' && $warningLetter->file_path && Storage::exists($warningLetter->file_path))
            <p><strong>{{ __('Signed Letter') }}:</strong> A signed copy is available.</p>
        @endif
    </div>

    <div class="signature">
        <p>___________________________</p>
        <p>{{ __('Employee Signature') }}</p>
        <p>___________________________</p>
        <p>{{ __('HR Signature') }}</p>
    </div>

    <div class="print-button">
        <button onclick="window.print()">{{ __('Print') }}</button>
    </div>
</body>
</html>