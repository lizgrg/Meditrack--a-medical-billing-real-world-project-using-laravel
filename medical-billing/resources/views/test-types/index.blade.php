<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Types & Fees') }}
            </h2>
            <a href="{{ route('test-types.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                + New Test Type
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Fee</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Times Used</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($testTypes as $type)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $type->name }}</td>
                                <td class="px-4 py-3">Rs. {{ number_format($type->fee, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if($type->is_active)
                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $type->invoices_count }} invoice(s)</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('test-types.edit', $type) }}" class="text-indigo-600 hover:underline mr-3">Edit</a>
                                    @if($type->invoices_count === 0)
                                        <form action="{{ route('test-types.destroy', $type) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this test type?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">No test types yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
