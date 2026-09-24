<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Invoice') }} {{ $invoice->invoice_no }}
        </h2>
    </x-slot>

    <style>
        @media print {
            body * { visibility: hidden; }
            #printable-invoice, #printable-invoice * { visibility: visible; }
            #printable-invoice { position: absolute; top: 0; left: 0; width: 100%; }
        }
    </style>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md print:hidden">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end gap-3 mb-4 print:hidden">
                <button onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    🖨 Print Invoice
                </button>
            </div>

            <div id="printable-invoice" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 print:shadow-none">

                <div class="flex justify-between items-start mb-6 pb-6 border-b">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                        <p class="text-gray-500">{{ $invoice->invoice_no }}</p>
                    </div>
                    <div class="text-right">
                        @if($invoice->status === 'paid')
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">PAID</span>
                        @elseif($invoice->status === 'cancelled')
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-gray-200 text-gray-600">CANCELLED</span>
                        @else
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">UNPAID</span>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">{{ $invoice->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Candidate</h3>
                        <p class="font-medium">{{ $invoice->candidate->name }}</p>
                        <p class="text-sm text-gray-600">Passport: {{ $invoice->candidate->passport_no }}</p>
                        <p class="text-sm text-gray-600">{{ $invoice->candidate->profession }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Billed By</h3>
                        <p class="font-medium">{{ $invoice->creator->name }}</p>
                    </div>
                </div>

                <table class="min-w-full text-sm mb-6">
                    <thead>
                        <tr class="text-left text-gray-500 uppercase text-xs tracking-wider border-b">
                            <th class="py-2">Description</th>
                            <th class="py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3">{{ $invoice->testType->name }}</td>
                            <td class="py-3 text-right">Rs. {{ number_format($invoice->amount, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="py-3 font-semibold text-right">Total</td>
                            <td class="py-3 font-semibold text-right">Rs. {{ number_format($invoice->amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                @if($invoice->payment)
                    <div class="p-4 bg-green-50 border border-green-200 rounded-md mb-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Payment Received</h3>
                            <p class="text-sm text-green-800">
                                {{ ucfirst($invoice->payment->payment_method) }} &middot;
                                Rs. {{ number_format($invoice->payment->amount, 2) }} &middot;
                                {{ $invoice->payment->payment_date->format('d M Y') }}
                            </p>
                        </div>
                        <a href="{{ route('invoices.receipt', $invoice) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 transition print:hidden">
                            ⬇ Download Receipt
                        </a>
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md mb-6 flex justify-between items-center print:hidden">
                        <p class="text-sm text-yellow-800">No payment recorded yet for this invoice.</p>
                        @if(Route::has('payments.create'))
                            <a href="{{ route('payments.create', $invoice) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                Record Payment
                            </a>
                        @endif
                    </div>
                @endif

                <div class="flex justify-between print:hidden">
                    <a href="{{ route('invoices.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Back to Invoices</a>
                    <a href="{{ route('candidates.index') }}" class="text-sm text-gray-600 hover:underline">Back to Candidates</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>