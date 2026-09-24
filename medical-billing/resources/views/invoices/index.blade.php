<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-4 flex gap-2">
                    <a href="{{ route('invoices.index') }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ !$status ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">All</a>
                    <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ $status === 'unpaid' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">Unpaid</a>
                    <a href="{{ route('invoices.index', ['status' => 'paid']) }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ $status === 'paid' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">Paid</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th class="px-4 py-3">Invoice No</th>
                                <th class="px-4 py-3">Candidate</th>
                                <th class="px-4 py-3">Test Type</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $invoice->invoice_no }}</td>
                                    <td class="px-4 py-3">{{ $invoice->candidate->name }}</td>
                                    <td class="px-4 py-3">{{ $invoice->testType->name }}</td>
                                    <td class="px-4 py-3">Rs. {{ number_format($invoice->amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        @if($invoice->status === 'paid')
                                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                                        @elseif($invoice->status === 'cancelled')
                                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600">Cancelled</span>
                                        @else
                                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $invoice->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No invoices yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
