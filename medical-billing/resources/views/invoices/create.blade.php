<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Invoice') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Candidate</h3>
                <p class="text-lg font-medium">{{ $candidate->name }}</p>
                <p class="text-sm text-gray-600">Passport: {{ $candidate->passport_no }} &middot; {{ $candidate->profession }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($testTypes->isEmpty())
                    <p class="text-gray-500">No active test types available. Ask an Admin to add one under Test Types & Fees.</p>
                @else
                    <form method="POST" action="{{ route('invoices.store', $candidate) }}">
                        @csrf

                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Select Medical Test Type</h3>

                        <div class="space-y-3 mb-6">
                            @foreach($testTypes as $type)
                                <label class="flex items-center justify-between border rounded-md p-4 cursor-pointer hover:bg-gray-50 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <span class="flex items-center">
                                        <input type="radio" name="test_type_id" value="{{ $type->id }}" required
                                               class="text-indigo-600 focus:ring-indigo-500" {{ old('test_type_id') == $type->id ? 'checked' : '' }}>
                                        <span class="ms-3 font-medium">{{ $type->name }}</span>
                                    </span>
                                    <span class="text-gray-700 font-semibold">Rs. {{ number_format($type->fee, 2) }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('candidates.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Generate Invoice
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
