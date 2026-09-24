<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $method = $request->query('method');

        $payments = Payment::with(['invoice.candidate', 'receiver'])
            ->when($method, fn ($query) => $query->where('payment_method', $method))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('payments.index', compact('payments', 'method'));
    }

    public function create(Invoice $invoice)
    {
        if ($invoice->payment) {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'This invoice has already been paid.');
        }

        return view('payments.create', compact('invoice'));
    }

    public function store(Request $request, Invoice $invoice)
    {
        if ($invoice->payment) {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'This invoice has already been paid.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,cheque,online'],
            'payment_date' => ['required', 'date'],
            'cheque_number' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:100'],
            'cheque_bank' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:255'],
            'transaction_id' => ['required_if:payment_method,online', 'nullable', 'string', 'max:255'],
        ]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $invoice->amount, // full payment, matches the invoice total
            'cheque_number' => $validated['cheque_number'] ?? null,
            'cheque_bank' => $validated['cheque_bank'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'received_by' => $request->user()->id,
            'payment_date' => $validated['payment_date'],
        ]);

        $invoice->update(['status' => 'paid']);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', "Payment recorded successfully for invoice {$invoice->invoice_no}. Receipt is ready.");
    }

    public function edit(Payment $payment)
    {
        $payment->load('invoice.candidate');

        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,cheque,online'],
            'payment_date' => ['required', 'date'],
            'cheque_number' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:100'],
            'cheque_bank' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:255'],
            'transaction_id' => ['required_if:payment_method,online', 'nullable', 'string', 'max:255'],
        ]);

        $payment->update([
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'cheque_number' => $validated['cheque_number'] ?? null,
            'cheque_bank' => $validated['cheque_bank'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
        ]);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment record updated.');
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;

        $payment->delete();

        $invoice->update(['status' => 'unpaid']);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment voided. Invoice reverted to unpaid.');
    }
}
