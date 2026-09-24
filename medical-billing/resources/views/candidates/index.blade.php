<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Candidates') }}
            </h2>
            <a href="{{ route('candidates.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                + New Candidate
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="GET" action="{{ route('candidates.index') }}" class="mb-4 flex gap-2">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Search by name or passport no..."
                           class="border-gray-300 rounded-md shadow-sm w-full max-w-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit"
                            class="px-4 py-2 bg-gray-200 rounded-md text-sm font-semibold hover:bg-gray-300">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('candidates.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline self-center">Clear</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th class="px-4 py-3">Passport No</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Address</th>
                                <th class="px-4 py-3">Profession</th>
                                <th class="px-4 py-3">Reference</th>
                                <th class="px-4 py-3">Registered</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($candidates as $candidate)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $candidate->passport_no }}</td>
                                    <td class="px-4 py-3">{{ $candidate->name }}</td>
                                    <td class="px-4 py-3">{{ Str::limit($candidate->address, 30) }}</td>
                                    <td class="px-4 py-3">{{ $candidate->profession }}</td>
                                    <td class="px-4 py-3">{{ $candidate->reference ?: '—' }}</td>
                                    <td class="px-4 py-3">{{ $candidate->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('invoices.create', $candidate) }}" class="text-green-700 font-medium hover:underline mr-3">Bill</a>
                                        @auth
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('candidates.edit', $candidate) }}" class="text-indigo-600 hover:underline mr-3">Edit</a>
                                                <form action="{{ route('candidates.destroy', $candidate) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Delete this candidate record? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                </form>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        No candidates registered yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $candidates->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
