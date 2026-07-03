<nav class="flex flex-col lg:h-screen max-h-full bg-white w-full overflow-hidden">
    <!-- Logo -->
    <div class="h-20 flex items-center px-8 shrink-0">
        @if(auth()->user()->role->nama == 'ASESOR')
            <a href="{{ route('asesor.dashboard') }}" class="flex items-center gap-3">
        @else
                <a href="{{ route('auditi.dashboard') }}" class="flex items-center gap-3">
            @endif
                <x-application-logo class="block h-10 w-auto fill-current text-indigo-600" />
            </a>
    </div>
    <div class="bg-gradient-to-br from-[#88A4F4]/15 to-slate-50 p-4">
        <div class="flex items-start gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#ffffff] text-[#152F79]">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">
                    Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Selamat bekerja hari ini
                </p>
            </div>
        </div>
    </div>
    <!-- Menu -->
    <div class="flex-1 min-h-0 px-3 py-4 space-y-2 overflow-y-auto overscroll-contain touch-pan-y">
        <p class="px-3 text-[12px] font-semibold text-gray-400 uppercase tracking-wider">
            Menu
        </p>
        @if(auth()->user()->role->nama == 'ASESOR')
            <x-nav-link @click="sidebarOpen = false" :href="route('asesor.dashboard')"
                :active="request()->routeIs('asesor.dashboard')" class="flex items-center gap-3 w-full">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </x-nav-link>
            <x-nav-link @click="sidebarOpen = false" :href="route('asesor.audit.index')"
                :active="request()->routeIs('asesor.audit.*')" class="flex items-center gap-3 w-full">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                <span>Audit</span>
            </x-nav-link>
        @else
            <x-nav-link @click="sidebarOpen = false" :href="route('auditi.dashboard')"
                :active="request()->routeIs('auditi.dashboard')" class="flex items-center gap-3 w-full">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </x-nav-link>
            <x-nav-link @click="sidebarOpen = false" :href="route('temuan.index')" :active="request()->routeIs('temuan.*')"
                class="flex items-center gap-3 w-full">
                <i data-lucide="file-warning" class="flex items-start w-5 h-5"></i>
                <span>Temuan Saya</span>
            </x-nav-link>
            @if(in_array(auth()->user()->role->nama, ['KPS', 'TEKNISI']))
                <x-nav-link @click="sidebarOpen = false" :href="route('auditi.hasil-auditor.index')"
                    :active="request()->routeIs('auditi.hasil-auditor.*')" class="flex items-center gap-3 w-full">
                    <i data-lucide="file-check-2" class="w-5 h-5"></i>
                    <span>Hasil Auditor</span>
                </x-nav-link>
            @endif
            {{-- MASTER DATA KHUSUS ADMIN PRODI --}}
            @if(auth()->user()->role->nama == 'ADMIN_PRODI')
                <div class="pt-1 space-y-2">
                    <p class="px-3 mb-2 text-[12px] font-semibold text-gray-400 uppercase tracking-wider">
                        Master Data
                    </p>
                    <x-nav-link @click="sidebarOpen = false" :href="route('admin.periode.index')"
                        :active="request()->routeIs('admin.periode.*')" class="flex items-center gap-3 w-full">
                        <i data-lucide="calendar-range" class="w-5 h-5"></i>
                        <span>Periode</span>
                    </x-nav-link>
                    <x-nav-link @click="sidebarOpen = false" :href="route('admin.users.index')"
                        :active="request()->routeIs('admin.users.*')" class="flex items-center gap-3 w-full">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Users</span>
                    </x-nav-link>
                    <x-nav-link @click="sidebarOpen = false" :href="route('admin.units.index')"
                        :active="request()->routeIs('admin.units.*')" class="flex items-center gap-3 w-full">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        <span>Units</span>
                    </x-nav-link>
                    <x-nav-link @click="sidebarOpen = false" :href="route('admin.templates.index')"
                        :active="request()->routeIs('admin.templates.*')" class="flex items-center gap-3 w-full">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                        <span>Template Instrumen</span>
                    </x-nav-link>
                </div>
            @endif
        @endif
    </div>
</nav>