<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                        <input type="date" name="from" id="from" value="{{ $from }}"
                               class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                        <input type="date" name="to" id="to" value="{{ $to }}"
                               class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700">
                        Apply
                    </button>

                    <div class="flex gap-2 ml-auto">
                        <a href="{{ route('reports.index', ['from' => now()->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                           class="px-3 py-2 bg-gray-100 rounded-md text-sm hover:bg-gray-200">Today</a>
                        <a href="{{ route('reports.index', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->endOfMonth()->format('Y-m-d')]) }}"
                           class="px-3 py-2 bg-gray-100 rounded-md text-sm hover:bg-gray-200">This Month</a>
                        <a href="{{ route('reports.index', ['from' => now()->startOfYear()->format('Y-m-d'), 'to' => now()->endOfYear()->format('Y-m-d')]) }}"
                           class="px-3 py-2 bg-gray-100 rounded-md text-sm hover:bg-gray-200">This Year</a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Total Candidates Registered</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCandidates }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Total Collection</p>
                    <p class="text-3xl font-bold text-gray-800">Rs. {{ number_format($totalCollection, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Across all payment methods</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Payment Breakdown</h3>
                    <a href="{{ route('reports.export', ['from' => $from, 'to' => $to]) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 transition">
                        ⬇ Download Excel Report
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-md">
                        <p class="text-xs text-gray-500 uppercase">Cash</p>
                        <p class="text-xl font-semibold">Rs. {{ number_format($breakdown['cash'], 2) }}</p>
                        <p class="text-xs text-gray-400">{{ $counts['cash'] }} transaction(s)</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-md">
                        <p class="text-xs text-gray-500 uppercase">Cheque</p>
                        <p class="text-xl font-semibold">Rs. {{ number_format($breakdown['cheque'], 2) }}</p>
                        <p class="text-xs text-gray-400">{{ $counts['cheque'] }} transaction(s)</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-md">
                        <p class="text-xs text-gray-500 uppercase">Online</p>
                        <p class="text-xl font-semibold">Rs. {{ number_format($breakdown['online'], 2) }}</p>
                        <p class="text-xs text-gray-400">{{ $counts['online'] }} transaction(s)</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
