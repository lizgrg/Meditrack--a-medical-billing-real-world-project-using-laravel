<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
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
                    <a href="{{ route('payments.index') }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ !$method ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">All</a>
                    <a href="{{ route('payments.index', ['method' => 'cash']) }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ $method === 'cash' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">Cash</a>
                    <a href="{{ route('payments.index', ['method' => 'cheque']) }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ $method === 'cheque' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">Cheque</a>
                    <a href="{{ route('payments.index', ['method' => 'online']) }}"
                       class="px-3 py-1.5 rounded-md text-sm {{ $method === 'online' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">Online</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th class="px-4 py-3">Invoice No</th>
                                <th class="px-4 py-3">Candidate</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Details</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Received By</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($payments as $payment)
                                <tr>
                                    <td class="px-4 py-3 font-medium">
                                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-indigo-600 hover:underline">
                                            {{ $payment->invoice->invoice_no }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $payment->invoice->candidate->name }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $payment->payment_method }}</td>
                                    <td class="px-4 py-3">Rs. {{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-gray-500 text-xs">
                                        @if($payment->payment_method === 'cheque')
                                            #{{ $payment->cheque_number }} &middot; {{ $payment->cheque_bank }}
                                        @elseif($payment->payment_method === 'online')
                                            TXN: {{ $payment->transaction_id }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ $payment->receiver->name }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('payments.edit', $payment) }}" class="text-indigo-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Void this payment? The invoice will revert to unpaid.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Void</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $payments->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
