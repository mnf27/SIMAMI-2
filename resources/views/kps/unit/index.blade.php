<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div x-cloak x-data="{ openCreateUnit:false, openEditUnit:false, editUnit:{id:'', nama:'', jenis:'', lokasi:''} }" class="py-[15px] lg:py-[14px] lg:py-[12px]">
        <div class="max-w-[1700px] mx-auto">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="building-2" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Manajemen Unit
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Kelola unit Program Studi dan Laboratorium yang digunakan
                            dalam proses Audit Mutu Internal.
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="rounded-2xl bg-white/10 backdrop-blur-sm px-5 py-3 min-w-full lg:min-w-[300px] transition-all duration-300 shadow-lg hover:-translate-y-1 hover:shadow-xl">
                            <p class="text-xs text-blue-100 font-medium">
                                Total Unit
                            </p>
                            <div class="mt-1 flex items-center gap-3">
                                <h3 class="text-4xl font-bold text-[#6EFF53]">
                                    {{ $totalUnit }}
                                </h3>
                                <span class="text-sm text-blue-100">
                                    unit terdaftar
                                </span>
                            </div>
                            <div class="mt-2 flex items-center gap-2 text-xs text-blue-100">
                                <i data-lucide="building-2" class="w-3 h-3"></i>
                                Unit Prodi dan Laboratorium SIMAMI
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Total Unit
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalUnit }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#1E3A8A]/5 text-[#1E3A8A]">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                {{-- PRODI --}}
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Unit Prodi
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
                                Unit Lab
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
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm mb-3">
                <form method="GET">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400">
                        </i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, jenis, atau lokasi unit..." class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm">
                    </div>
                </form>
                @if(request('search'))
                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $units->total() }} unit ditemukan
                            </p>
                            <p class="text-xs text-slate-500">
                                untuk pencarian "{{ request('search') }}"
                            </p>
                        </div>
                        <a href="{{ route('kps.units.index') }}"
                            class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-200">
                            Reset
                        </a>
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div
                    class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-800">
                            Daftar Unit
                        </h3>
                        <p class="text-sm text-slate-500">
                            Kelola data unit Program Studi dan Laboratorium
                        </p>
                    </div>
                    <button type="button" @click="openCreateUnit = true"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#1E3A8A] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#17306F]">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Unit
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($units as $unit)
                        <div
                            class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl {{ strtoupper($unit->jenis) == 'PRODI' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                                            @if(strtoupper($unit->jenis) == 'PRODI')
                                                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                            @else
                                                <i data-lucide="flask-conical" class="w-5 h-5"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-slate-800">
                                                {{ $unit->nama }}
                                            </h3>
                                            <p class="text-sm text-slate-500">
                                                {{ $unit->lokasi ?: 'Lokasi belum diisi' }}
                                            </p>
                                        </div>
                                    </div>
                                    {{-- BADGES --}}
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-medium {{ strtoupper($unit->jenis) == 'PRODI' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                                            {{ ucfirst(strtolower($unit->jenis)) }}
                                        </span>
                                        @if($unit->lokasi)
                                            <span
                                                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                {{ $unit->lokasi }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- RIGHT --}}
                                <div class="flex items-center gap-2 shrink-0">
                                    <button @click="editUnit = {id: '{{ $unit->id }}', nama: '{{ $unit->nama }}', jenis: '{{ $unit->jenis }}', lokasi: '{{ $unit->lokasi }}' }; openEditUnit = true"
                                        class="inline-flex items-center gap-1 rounded-xl bg-amber-50 px-3 py-2 text-sm font-medium text-amber-600 transition hover:bg-amber-100">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                        Edit
                                    </button>
                                    <form action="{{ route('kps.units.destroy', $unit) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="inline-flex items-center gap-1 rounded-xl bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                            <div class="flex flex-col items-center">
                                <div
                                    class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                    <i data-lucide="building-2" class="w-6 h-6"></i>
                                </div>
                                @if(request('search'))
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Unit tidak ditemukan.
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Coba gunakan kata kunci lain.
                                    </p>
                                @else
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Belum ada unit.
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Tambahkan unit Program Studi atau Laboratorium terlebih dahulu.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforelse
                    
                </div>
            </div>
            <div class="mt-4">
                {{ $units->links() }}
            </div>
        </div>
        <x-ui.modal open="openCreateUnit" title="Buat Unit" maxWidth="max-w-xl">
            <form action="{{ route('kps.units.store') }}" method="POST">
                @csrf
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        {{-- NAMA AUDIT --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Nama Unit
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                placeholder="Contoh: D3 Teknolofi informasi / Laboratorium..."
                                class="w-full rounded-xl border-slate-200 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">
                        </div>
                        {{-- PERIODE --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Jenis Unit
                            </label>
                            <x-ui.custom-select name="jenis" placeholder="Pilih Jenis Unit" valueField="id" :options="[['id' => 'PRODI', 'display' => 'Program Studi'],['id' => 'LAB', 'display' => 'Laboratorium']]"
                                labelField="display" />
                        </div>
                        {{-- LOKASI --}}
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Lokasi
                            </label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                                placeholder="Contoh: PSDKU Lumajang"
                                class="w-full rounded-xl border-slate-200 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-200 p-5">
                    <button type="button" @click="openCreateUnit = false"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-[#1E3A8A] px-4 py-2 text-sm font-medium text-white hover:bg-[#152F79]">
                        Buat Unit
                    </button>
                </div>
            </form>
        </x-ui.modal>
        <x-ui.modal open="openEditUnit" title="Edit Unit" maxWidth="max-w-xl">
            <form :action="`/kps/units/${editUnit.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        {{-- NAMA --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Nama Unit
                            </label>
                            <input type="text" name="nama" x-model="editUnit.nama" class="w-full rounded-xl border-slate-200 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" >
                        </div>
                        {{-- JENIS --}}
                        <div>
                            <label
                                class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Jenis Unit
                            </label>
                            <x-ui.custom-select name="jenis" model="editUnit.jenis" placeholder="Pilih Jenis Unit" valueField="id" labelField="display" :options="[
                                    ['id' => 'PRODI', 'display' => 'Program Studi'],
                                    ['id' => 'LAB', 'display' => 'Laboratorium']
                                ]" />
                        </div>
                        {{-- LOKASI --}}
                        <div>
                            <label
                                class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Lokasi
                            </label>
                            <input type="text" name="lokasi" x-model="editUnit.lokasi" class="w-full rounded-xl border-slate-200 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-200 p-5">
                    <button
                        type="button"
                        @click="openEditUnit = false"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-xl bg-[#1E3A8A] px-4 py-2 text-sm font-medium text-white hover:bg-[#152F79]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-app-layout>