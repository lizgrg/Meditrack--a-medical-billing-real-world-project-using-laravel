<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment') }} — {{ $payment->invoice->invoice_no }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" x-data="{ method: '{{ old('payment_method', $payment->payment_method) }}' }">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="text-sm text-gray-600 mb-4">
                    {{ $payment->invoice->candidate->name }} &middot; Rs. {{ number_format($payment->amount, 2) }}
                </p>

                <form method="POST" action="{{ route('payments.update', $payment) }}">
                    @csrf
                    @method('PUT')

                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Payment Method</h3>

                    <div class="grid grid-cols-3 gap-3 mb-6">
                        <label class="flex items-center justify-center border rounded-md p-3 cursor-pointer hover:bg-gray-50"
                               :class="method === 'cash' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'">
                            <input type="radio" name="payment_method" value="cash" x-model="method" class="sr-only">
                            <span class="font-medium">Cash</span>
                        </label>
                        <label class="flex items-center justify-center border rounded-md p-3 cursor-pointer hover:bg-gray-50"
                               :class="method === 'cheque' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'">
                            <input type="radio" name="payment_method" value="cheque" x-model="method" class="sr-only">
                            <span class="font-medium">Cheque</span>
                        </label>
                        <label class="flex items-center justify-center border rounded-md p-3 cursor-pointer hover:bg-gray-50"
                               :class="method === 'online' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'">
                            <input type="radio" name="payment_method" value="online" x-model="method" class="sr-only">
                            <span class="font-medium">Online</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div x-show="method === 'cheque'" x-cloak class="space-y-4 mb-4 p-4 bg-gray-50 rounded-md">
                        <div>
                            <label for="cheque_number" class="block text-sm font-medium text-gray-700">Cheque Number</label>
                            <input type="text" name="cheque_number" id="cheque_number" value="{{ old('cheque_number', $payment->cheque_number) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="cheque_bank" class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" name="cheque_bank" id="cheque_bank" value="{{ old('cheque_bank', $payment->cheque_bank) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div x-show="method === 'online'" x-cloak class="mb-4 p-4 bg-gray-50 rounded-md">
                        <label for="transaction_id" class="block text-sm font-medium text-gray-700">Transaction ID</label>
                        <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('payments.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
