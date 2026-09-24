<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Invoice;
use App\Models\TestType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $invoices = Invoice::with(['candidate', 'testType', 'payment'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'status'));
    }

    public function create(Candidate $candidate)
    {
        $testTypes = TestType::active()->orderBy('name')->get();

        return view('invoices.create', compact('candidate', 'testTypes'));
    }

    public function store(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'test_type_id' => ['required', 'exists:test_types,id'],
        ]);

        $testType = TestType::findOrFail($validated['test_type_id']);

        $invoice = Invoice::create([
            'candidate_id' => $candidate->id,
            'test_type_id' => $testType->id,
            'amount' => $testType->fee, // snapshot at time of billing
            'status' => 'unpaid',
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_no} generated for {$candidate->name}.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['candidate', 'testType', 'payment', 'creator']);

        return view('invoices.show', compact('invoice'));
    }

    public function receipt(Invoice $invoice)
    {
        if (! $invoice->payment) {
            abort(404, 'No payment recorded for this invoice yet.');
        }

        $invoice->load(['candidate', 'testType', 'payment.receiver', 'creator']);

        $pdf = Pdf::loadView('receipts.pdf', compact('invoice'));

        return $pdf->download("Receipt-{$invoice->invoice_no}.pdf");
    }
}
