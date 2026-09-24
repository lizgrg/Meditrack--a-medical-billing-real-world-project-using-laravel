<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $invoice->invoice_no }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 13px;
            margin: 40px;
        }
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }
        .header-right {
            text-align: right;
        }
        h1 {
            font-size: 24px;
            margin: 0 0 4px 0;
            letter-spacing: 1px;
        }
        .muted {
            color: #6b7280;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            background-color: #d1fae5;
            color: #065f46;
        }
        .section {
            display: table;
            width: 100%;
            margin-bottom: 24px;
        }
        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table.items th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 1px solid #d1d5db;
            padding: 8px 0;
        }
        table.items td {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        table.items .amount {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            font-size: 15px;
            border-bottom: none;
            padding-top: 12px;
        }
        .payment-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .payment-box .label {
            color: #15803d;
        }
        .footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>RECEIPT</h1>
            <p class="muted">{{ $invoice->invoice_no }}</p>
        </div>
        <div class="header-right">
            <span class="badge">PAID</span>
            <p class="muted">{{ $invoice->payment->payment_date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="section">
        <div class="col">
            <p class="label">Candidate</p>
            <p class="value">{{ $invoice->candidate->name }}</p>
            <p class="muted">Passport: {{ $invoice->candidate->passport_no }}</p>
            <p class="muted">{{ $invoice->candidate->profession }}</p>
        </div>
        <div class="col">
            <p class="label">Billed By</p>
            <p class="value">{{ $invoice->creator->name }}</p>
            <p class="label" style="margin-top: 10px;">Received By</p>
            <p class="value">{{ $invoice->payment->receiver->name }}</p>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->testType->name }}</td>
                <td class="amount">Rs. {{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Paid</td>
                <td class="amount">Rs. {{ number_format($invoice->payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="payment-box">
        <p class="label">Payment Method</p>
        <p class="value" style="text-transform: capitalize;">{{ $invoice->payment->payment_method }}</p>
        @if($invoice->payment->payment_method === 'cheque')
            <p class="muted">Cheque No: {{ $invoice->payment->cheque_number }} &middot; Bank: {{ $invoice->payment->cheque_bank }}</p>
        @elseif($invoice->payment->payment_method === 'online')
            <p class="muted">Transaction ID: {{ $invoice->payment->transaction_id }}</p>
        @endif
    </div>

    <div class="footer">
        This is a computer-generated receipt. Generated on {{ now()->format('d M Y, h:i A') }}.
    </div>

</body>
</html>
