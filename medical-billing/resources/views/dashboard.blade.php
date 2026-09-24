<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 px-8">
        <p class="text-gray-500 mb-6">Welcome back, {{ auth()->user()->name }}.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-teal-600">
                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Candidates Today</p>
                <p class="text-3xl font-bold text-gray-800">{{ $todayCandidates }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-teal-600">
                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Collection Today</p>
                <p class="text-3xl font-bold text-gray-800">Rs. {{ number_format($todayCollection, 2) }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Unpaid Invoices</p>
                <p class="text-3xl font-bold text-gray-800">{{ $unpaidCount }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user()->isAdmin() || auth()->user()->isReceptionist())
                    <a href="{{ route('candidates.create') }}" class="px-4 py-2 bg-teal-700 text-white rounded-md text-sm font-medium hover:bg-teal-800">
                        + Register Candidate
                    </a>
                    <a href="{{ route('candidates.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">
                        View Candidates
                    </a>
                @endif
                @if(auth()->user()->isAdmin() || auth()->user()->isAccountant())
                    <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">
                        View Reports
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
