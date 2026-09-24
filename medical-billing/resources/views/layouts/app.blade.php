<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MediTrack {{ isset($header) ? '— ' . strip_tags($header) : '' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-30">

            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <div class="w-8 h-8 rounded-lg bg-teal-700 flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="font-bold text-lg text-gray-800 tracking-tight">MediTrack</span>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

                @php
                    $user = auth()->user();
                    $isActive = fn ($pattern) => request()->routeIs($pattern) ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
                @endphp

                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('dashboard') }}">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                @if($user->isAdmin() || $user->isReceptionist())
                    <a href="{{ route('candidates.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('candidates.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Candidates
                    </a>
                @endif

                @if($user->isAdmin() || $user->isReceptionist() || $user->isAccountant())
                    <a href="{{ route('invoices.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('invoices.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Invoices
                    </a>
                @endif

                @if($user->isAdmin() || $user->isAccountant())
                    <a href="{{ route('payments.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('payments.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Payments
                    </a>
                @endif

                @if($user->isAdmin() || $user->isAccountant())
                    <a href="{{ route('reports.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('reports.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Reports
                    </a>
                @endif

                @if($user->isAdmin())
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin</p>
                        <a href="{{ route('test-types.index') }}"
                           class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('test-types.*') }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Test Types & Fees
                        </a>
                        <a href="{{ route('users.index') }}"
                           class="flex items-center px-3 py-2 rounded-md text-sm transition {{ $isActive('users.*') }}">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                            </svg>
                            User Accounts
                        </a>
                    </div>
                @endif

            </nav>

            <div class="border-t border-gray-200 p-3">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex items-center px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                        <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-semibold text-xs mr-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="text-left flex-1 min-w-0">
                            <p class="font-medium truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ $user->role }}</p>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute bottom-full left-0 mb-2 w-full bg-white rounded-md shadow-lg border border-gray-200 py-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 ml-64">
            @isset($header)
                <header class="bg-white border-b border-gray-200 h-16 flex items-center px-8">
                    <div class="w-full">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>
