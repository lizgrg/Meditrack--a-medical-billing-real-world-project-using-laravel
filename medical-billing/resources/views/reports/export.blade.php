<table>
    <tr>
        <td colspan="4"><strong>Medical Billing Report</strong></td>
    </tr>
    <tr>
        <td colspan="4">Period: {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</td>
    </tr>
    <tr><td colspan="4"></td></tr>

    <tr>
        <td><strong>Total Candidates Registered</strong></td>
        <td>{{ $totalCandidates }}</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td><strong>Total Collection</strong></td>
        <td>Rs. {{ number_format($totalCollection, 2) }}</td>
        <td colspan="2"></td>
    </tr>
    <tr><td colspan="4"></td></tr>

    <tr>
        <td colspan="4"><strong>Payment Breakdown</strong></td>
    </tr>
    <tr>
        <td><strong>Method</strong></td>
        <td><strong>Amount</strong></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>Cash</td>
        <td>Rs. {{ number_format($breakdown['cash'], 2) }}</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>Cheque</td>
        <td>Rs. {{ number_format($breakdown['cheque'], 2) }}</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>Online</td>
        <td>Rs. {{ number_format($breakdown['online'], 2) }}</td>
        <td colspan="2"></td>
    </tr>
    <tr><td colspan="4"></td></tr>

    <tr>
        <td colspan="4"><strong>Transaction Detail</strong></td>
    </tr>
    <tr>
        <td><strong>Date</strong></td>
        <td><strong>Invoice No</strong></td>
        <td><strong>Candidate</strong></td>
        <td><strong>Test Type</strong></td>
        <td><strong>Method</strong></td>
        <td><strong>Amount</strong></td>
        <td><strong>Received By</strong></td>
    </tr>
    @forelse($payments as $payment)
        <tr>
            <td>{{ $payment->payment_date->format('d M Y') }}</td>
            <td>{{ $payment->invoice->invoice_no }}</td>
            <td>{{ $payment->invoice->candidate->name }}</td>
            <td>{{ $payment->invoice->testType->name }}</td>
            <td>{{ ucfirst($payment->payment_method) }}</td>
            <td>Rs. {{ number_format($payment->amount, 2) }}</td>
            <td>{{ $payment->receiver->name }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No transactions in this period.</td>
        </tr>
    @endforelse
</table>
