<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overscroll-none">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body x-data="{ sidebarOpen: false }"
    class="font-sans antialiased bg-[#F6F8FF] overscroll-none overflow-y-auto overflow-x-hidden">
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/40 z-[990] lg:hidden">
    </div>
    <div class="flex min-h-screen">
        <aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-0 left-0 w-[240px] lg:w-[244px] z-[995] transform transition-all duration-300 ease-out lg:translate-x-0">
            <nav class="flex shadow-lg rounded-br-3xl lg:rounded-none overflow-hidden">
                @include('layouts.navigation')
            </nav>
        </aside>
        <!-- Main Content -->
        <div class="lg:ml-[244px] flex-1 h-screen flex flex-col">
            <!-- Mobile Topbar -->
            <div class="lg:hidden fixed top-0 left-0 right-0 h-14 bg-[#E5ECFF] z-[985]">
                <div class="h-full px-3 flex items-center justify-between">
                    <!-- Logo -->
                    <x-application-logo-mobile />
                    <div class="flex items-center gap-1">
                        <!-- Notification -->
                        <div x-data="{ openNotif:false, count: {{ auth()->user()->unreadNotifications()->count() }}, async refreshNotif() { try { const res = await fetch('/notifications/count'); const data = await res.json(); this.count = data.count; } catch (e) { console.error(e); } } }"
                            x-init="setInterval( () => refreshNotif(), 5000 )" class="relative">
                            <button @click="openNotif = !openNotif"
                                class="relative flex items-center justify-center w-7 h-7 rounded-lg hover:bg-[#152F79]/10 text-[#152F79] transition">
                                <i data-lucide="bell" class="w-4 h-4 ">
                                </i>
                                <template x-if="count > 0">
                                    <span x-text="count"
                                        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                                    </span>
                                </template>
                            </button>
                            {{-- DROPDOWN --}}
                            <div x-show="openNotif" @click.away="openNotif = false" x-transition x-cloak
                                class="fixed top-14 left-1/2 -translate-x-1/2 w-[92%] max-w-sm bg-white rounded-md shadow-xl z-[985] overflow-hidden">
                                <div class="py-3 px-4 border-b">
                                    <h3 class="text-md font-semibold text-slate-800">
                                        Notifications
                                    </h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->fresh()->notifications->take(10) as $notif)
                                        <a href="{{ route('notification.read', $notif->id) }}"
                                            class="block px-4 py-2 border-b hover:bg-[#E8EEFF] transition">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800">
                                                        {{ $notif->data['title'] }}
                                                    </p>
                                                    <p class="text-sm text-slate-500">
                                                        {{ $notif->data['message'] }}
                                                    </p>
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        {{ $notif->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                @if(is_null($notif->read_at))
                                                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-2">
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-4 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <i data-lucide="bell-off" class="w-6 h-6 text-slate-300">
                                                </i>
                                                <p class="text-xs text-slate-400">
                                                    Semua notifikasi sudah dibaca
                                                </p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <!-- Hamburger -->
                        <button @click="sidebarOpen = true"
                            class="flex items-center justify-center w-7 h-7 rounded-lg text-[#152F79] hover:bg-[#152F79]/10 transition">
                            <i data-lucide="menu" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Fixed Header -->
            @isset($header)
                <header
                    class="fixed top-[60px] lg:top-0 left-1 right-1 lg:left-[244px] lg:right-0 h-14 lg:h-16 bg-[#152F79]/90 rounded-md lg:rounded-none shadow-md backdrop-blur-sm z-[980]">
                    <div class="h-full pl-3 flex items-center justify-between">
                        <!-- Left -->
                        <form action="{{ route('periode.set') }}" method="POST">
                            @csrf
                            @php
                                $periodes = \App\Models\Periode::whereHas('audits')
                                    ->orderByDesc('kode')
                                    ->get();
                                $selectedPeriode = session('periode');
                                $activePeriode = \App\Models\Periode::where('is_active', true)->first();
                            @endphp
                            <div x-cloak x-data="{ open: false }" class="relative md:flex">
                                <!-- Trigger -->
                                <button type="button" @click="open = !open"
                                    class="flex items-center gap-2 rounded-lg bg-white shadow-sm">
                                    <!-- Icon -->
                                    <div
                                        class="flex items-center justify-center lg:w-10 lg:h-10 w-9 h-9 rounded-lg bg-[#E0E8FF]">
                                        <i data-lucide="calendar-range" class="w-4 h-4 text-[#1E3A8A]"></i>
                                    </div>
                                    <!-- Text -->
                                    <div class="text-left">
                                        <p class="text-xs lg:text-sm font-medium text-slate-600">
                                            @if($selectedPeriode)
                                                {{ $selectedPeriode }}
                                                {{ \App\Helpers\SemesterHelper::label($selectedPeriode) }}
                                            @else
                                                Semua Periode
                                            @endif
                                        </p>
                                        @if($activePeriode && $selectedPeriode != $activePeriode->kode)
                                            <p class="text-[10px] text-emerald-600 font-medium">
                                                Aktif: {{ $activePeriode->kode }}
                                            </p>
                                        @endif
                                    </div>
                                    <!-- Arrow -->
                                    <div class="px-2">
                                        <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 transition"
                                            :class="{ 'rotate-180': open }"></i>
                                    </div>
                                </button>
                                <!-- Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute top-full mt-1 w-full rounded-lg bg-white shadow-xl overflow-y-auto custom-scroll z-[980]">
                                    <div class="max-h-80 overflow-y-auto custom-scroll">
                                        <!-- Semua -->
                                        <button type="submit" name="periode" value=""
                                            class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#E0E8FF] transition text-left">
                                            <div>
                                                <p class="lg:text-sm text-xs font-semibold text-slate-700">
                                                    Semua Periode
                                                </p>
                                            </div>
                                            @if(! $selectedPeriode)
                                                <i data-lucide="check" class="w-4 h-4 text-[#1E3A8A]"></i>
                                            @endif
                                        </button>
                                        <!-- List -->
                                        @foreach($periodes as $periode)
                                            <button type="submit" name="periode" value="{{ $periode->kode }}"
                                                class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#E0E8FF] transition text-left">
                                                <div>
                                                    <p class="lg:text-sm text-xs font-semibold text-slate-700">
                                                        {{ $periode->kode }}
                                                    </p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $periode->label }}
                                                    </p>
                                                </div>
                                                @if($selectedPeriode == $periode->kode)
                                                    <i data-lucide="check" class="w-4 h-4 text-[#1E3A8A]"></i>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Right -->
                        <div class="flex items-center gap-3 pl-3 border-l border-white/50">
                            <!-- Notification -->
                            <div x-data="{ openNotif:false, count: {{ auth()->user()->unreadNotifications()->count() }}, async refreshNotif() { try { const res = await fetch('/notifications/count'); const data = await res.json(); this.count = data.count; } catch (e) { console.error(e); } } }"
                                x-init="setInterval( () => refreshNotif(), 5000 )" class="relative hidden lg:block">
                                <button @click="openNotif = !openNotif"
                                    class="relative flex items-center justify-center w-10 h-10 rounded-xl hover:bg-[#5976C8] text-white hover:text-[#ffffff] transition">
                                    <i data-lucide="bell" class="w-5 h-5 ">
                                    </i>
                                    <template x-if="count > 0">
                                        <span x-text="count"
                                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                                        </span>
                                    </template>
                                </button>
                                {{-- DROPDOWN --}}
                                <div x-show="openNotif" @click.away="openNotif = false" x-transition x-cloak
                                    class="absolute right-0 mt-1 w-80 bg-white rounded-xl shadow-xl z-[980]">
                                    <div class="p-4 border-b">
                                        <h3 class="font-semibold text-slate-800">
                                            Notifications
                                        </h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @forelse(auth()->user()->fresh()->notifications->take(10) as $notif)
                                            <a href="{{ route('notification.read', $notif->id) }}"
                                                class="block px-4 py-2 border-b hover:bg-[#E8EEFF] transition">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-semibold text-slate-800">
                                                            {{ $notif->data['title'] }}
                                                        </p>
                                                        <p class="text-sm text-slate-500 mt-1">
                                                            {{ $notif->data['message'] }}
                                                        </p>
                                                        <p class="text-xs text-slate-400 mt-2">
                                                            {{ $notif->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    @if(is_null($notif->read_at))
                                                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-2">
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                        @empty
                                            <div class="p-4 text-center">
                                                <div class="flex flex-col items-center justify-center gap-2">
                                                    <i data-lucide="bell-off" class="w-6 h-6 text-slate-300">
                                                    </i>
                                                    <p class="text-xs text-slate-400">
                                                        Semua notifikasi sudah dibaca
                                                    </p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <!-- User -->
                            <div x-data="{ openUser: false }" class="relative">
                                <button id="userMenuButton" @click="openUser = !openUser"
                                    class="flex items-center lg:gap-4 rounded-l-md lg:rounded-l-xl bg-white/30 hover:bg-white/40 backdrop-blur-md px-2 lg:px-4 py-2.5 lg:py-2 transition-all duration-200">
                                    <!-- User -->
                                    <div class="hidden lg:block text-left">
                                        <p class="text-sm font-semibold text-white leading-none">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="mt-1 text-xs text-blue-100">
                                            {{ Auth::user()->role->nama }}
                                        </p>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-white transition"
                                        :class="{ 'rotate-180': openUser }"></i>
                                </button>
                                <!-- Dropdown -->
                                <div id="userDropdown"
                                    class="hidden absolute right-0 mt-1 w-60 rounded-l-xl bg-white shadow-xl overflow-hidden z-[985]">
                                    <!-- Header -->
                                    <div
                                        class="flex items-center px-4 bg-gradient-to-br from-[#88A4F4]/30 to-slate-50 py-2 border-b border-slate-100">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-slate-800">
                                                {{ Auth::user()->name }}
                                            </p>
                                            <p class="text-xs lg:text-sm text-slate-500">
                                                {{ Auth::user()->email }}
                                            </p>
                                        </div>
                                        <span
                                            class="inline-flex items-center lg:hidden rounded-lg bg-[#152F79]/10 px-2 py-1 text-[10px] font-semibold text-[#152F79]">
                                            {{ Auth::user()->role->nama }}
                                        </span>
                                    </div>
                                    <!-- Menu -->
                                    <div class="p-2">
                                        <a href="{{ route('profile.edit') }}"
                                            class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 transition">
                                            <i data-lucide="user-circle" class="w-4 h-4"></i>
                                            <span>Profile</span>
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-red-500 hover:bg-red-50 transition">
                                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            @endisset
            <!-- Scrollable Content -->
            <main class="flex-1 flex flex-col">
                <div class="flex-1 pt-[113px] sm:pt-[104px] lg:pt-[64px] px-3 sm:px-3 lg:px-[15px]">
                    {{ $slot }}
                </div>
                <footer class="bg-[#D4D6DC]/50 px-4 pt-3 pb-2 lg:pt-4 lg:pb-3">
                    <div class="flex flex-col md:flex-row items-left justify-between gap-2">
                        <p class="text-xs font-medium text-slate-500">
                            © 2026 SIMAMI - Sistem Informasi Manajemen Audit Mutu Internal
                        </p>
                        <p class="text-xs text-slate-500">
                            Developed by Polinema PSDKU Lumajang
                        </p>
                    </div>
                </footer>
            </main>
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    <script>
        const button = document.getElementById('userMenuButton');
        const dropdown = document.getElementById('userDropdown');
        button.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });
        window.addEventListener('click', function (e) {
            if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>