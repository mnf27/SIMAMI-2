<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-[15px] lg:py-[14px] lg:py-[12px]">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="users" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Manajemen User
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Kelola seluruh akun pengguna SIMAMI mulai dari Admin, Auditor,
                            Asesor, hingga Auditi.
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="rounded-2xl bg-white/10 backdrop-blur-sm px-5 py-3 min-w-full lg:min-w-[300px] transition-all duration-300 shadow-lg hover:-translate-y-1 hover:shadow-xl">
                            <p class="text-xs text-blue-100 font-medium">
                                Total Pengguna
                            </p>
                            <div class="mt-1 flex items-center gap-3">
                                <h3 class="text-4xl font-bold text-[#6EFF53]">
                                    {{ $totalUser }}
                                </h3>
                                <span class="text-sm text-blue-100">
                                    akun terdaftar
                                </span>
                            </div>
                            <div class="mt-2 flex items-center gap-2 text-xs text-blue-100">
                                <i data-lucide="shield-check" class="w-3 h-3"></i>
                                Data pengguna aktif SIMAMI
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                {{-- AUDITOR --}}
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Auditor
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalAuditor }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-amber-50 text-amber-600">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                {{-- AUDITI --}}
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Auditi
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalAuditi }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-green-50 text-green-600">
                            <i data-lucide="user-check" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                {{-- PRODI --}}
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                User Prodi
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalProdi }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                {{-- LAB --}}
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                User Lab
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalLab }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-purple-50 text-purple-600">
                            <i data-lucide="flask-conical" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-800">
                        Daftar User
                    </h3>
                    <p class="text-sm text-slate-500">
                        Kelola data pengguna SIMAMI
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.template') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 transition hover:bg-green-100">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Download Template
                        </a>
                        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="import-user"
                                class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#1E3A8A] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#17306F]">
                                <i data-lucide="upload" class="w-4 h-4"></i>
                                Import Excel
                            </label>
                            <input id="import-user" type="file" name="file" class="hidden"
                                onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm mb-3">
                <form method="GET">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400">
                        </i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email pengguna..."
                            class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm">
                    </div>
                </form>
                @if(request('search'))
                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $users->total() }} pengguna ditemukan
                            </p>
                            <p class="text-xs text-slate-500">
                                untuk pencarian "{{ request('search') }}"
                            </p>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-200">
                            Reset
                        </a>
                    </div>
                @endif
            </div>
            <div class="space-y-3">
                @forelse($users as $user)
                    <div
                        class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E0E8FF] text-md font-bold text-[#1E3A8A]">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">
                                            {{ $user->name }}
                                        </h3>
                                        <p class="text-sm text-slate-500">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">
                                        {{ $user->role?->nama_format }}
                                    </span>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        {{ $user->unit?->nama ?? 'Tanpa Unit' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                            @if(request('search'))
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    Pengguna tidak ditemukan.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Coba gunakan kata kunci lain.
                                </p>
                            @else
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    Belum ada pengguna.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Silakan import data user terlebih dahulu.
                                </p>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>