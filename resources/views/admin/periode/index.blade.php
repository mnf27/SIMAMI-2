<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-[15px] lg:py-[14px] lg:py-[12px]">
        <div class="max-w-[1700px] mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <!-- Background Icon -->
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="calendar-range" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">
                    <!-- Left -->
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Manajemen Periode
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Kelola periode audit yang digunakan dalam seluruh proses Audit Mutu Internal.
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="rounded-2xl bg-white/10 backdrop-blur-sm px-5 py-3 min-w-full lg:min-w-[300px] transition-all duration-300 shadow-lg hover:-translate-y-1 hover:shadow-xl">
                            <p class="text-xs text-blue-100 font-medium">
                                Periode Aktif
                            </p>
                            <div class="mt-1 flex items-center gap-3">
                                <h3 class="text-4xl font-bold text-[#6EFF53]">
                                    {{ $periodeAktif?->kode ?? '-' }}
                                </h3>
                            </div>
                            <div class="mt-2 flex items-center gap-2 text-xs text-blue-100">
                                <i data-lucide="clipboard-check" class="w-3 h-3"></i>
                                {{ $periodeAktif?->audits_count ?? 0 }} audit menggunakan periode ini
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
                {{-- TOTAL PERIODE --}}
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Total Periode
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalPeriode }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#1E3A8A]/5 text-[#1E3A8A]">
                            <i data-lucide="calendar-range" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Periode Digunakan
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $periodeDigunakan }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-green-50 text-green-600">
                            <i data-lucide="calendar-check" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Total Audit
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalAudit }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#E77817]/5 text-[#E77817]">
                            <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div x-data="{ tahun: '{{ old('tahun', now()->year) }}', semester: '{{ old('semester', '2') }}' }">
                <div x-data="{ openForm: false }"
                    class="mb-3 rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
                    <button @click="openForm = !openForm"
                        class="w-full px-4 py-3 flex items-center justify-between transition">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A]">
                                <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-slate-800">
                                    Tambah Periode Baru
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Buat periode audit baru
                                </p>
                            </div>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                            :class="{ 'rotate-180': openForm }">
                        </i>
                    </button>
                    <div x-cloak x-show="openForm" x-collapse class="py-3 px-4">
                        <form action="{{ route('admin.periode.store') }}" method="POST">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 pb-2 border-b border-slate-200">
                                @csrf
                                <div class="mb-2">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                        Tahun
                                    </label>
                                    <div class="flex items-center">
                                        <input type="number" name="tahun" x-model="tahun" min="2000" max="9999" step="1"
                                            placeholder="Masukkan tahun"
                                            class="flex-1 py-2.5 px-3 rounded-lg border border-slate-200 shadow-sm text-sm text-slate-700 bg-transparent">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                        Semester
                                    </label>
                                    <div x-data="{ openSemester:false }" class="relative">
                                        <input type="hidden" name="semester" x-model="semester">
                                        <button type="button" @click="openSemester = !openSemester"
                                            class="w-full flex items-center justify-between gap-2 rounded-lg bg-white border border-slate-200 focus:border-[#1E3A8A] focus:border-[#1E3A8A] focus:ring-[#1E3A8A] shadow-sm">
                                            <div class="flex items-center px-3 py-2.5">
                                                <p class="text-sm font-medium text-slate-600">
                                                    <span x-show="semester == '1'">
                                                        Genap
                                                    </span>
                                                    <span x-show="semester == '2'">
                                                        Ganjil
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="px-3">
                                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-600 transition"
                                                    :class="{ 'rotate-180': openSemester }">
                                                </i>
                                            </div>
                                        </button>
                                        <div x-cloak x-show="openSemester" @click.away="openSemester = false"
                                            x-transition placeholder="Select Semester"
                                            class="absolute top-full mt-1 w-full rounded-lg bg-white border border-slate-300 shadow-xl overflow-hidden z-20">
                                            <button type="button" @click="semester='1'; openSemester=false"
                                                class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#E0E8FF]">
                                                <span class="text-sm font-semibold text-slate-700">
                                                    Genap
                                                </span>
                                                <template x-if="semester == '1'">
                                                    <i data-lucide="check" class="w-4 h-4 text-[#1E3A8A]"></i>
                                                </template>
                                            </button>
                                            <button type="button" @click="semester='2'; openSemester=false"
                                                class="w-full flex items-center justify-between px-3 py-3 hover:bg-[#E0E8FF]">
                                                <span class="text-sm font-semibold text-slate-700">
                                                    Ganjil
                                                </span>
                                                <template x-if="semester == '2'">
                                                    <i data-lucide="check" class="w-4 h-4 text-[#1E3A8A]"></i>
                                                </template>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-col gap-3 pt-3 border-t border-slate-200 lg:flex-row lg:items-center lg:justify-between">
                                {{-- Preview --}}
                                <div class="text-sm text-slate-500">
                                    <span class="font-medium">
                                        Preview:
                                    </span>
                                    <span class="text-slate-700 font-semibold">
                                        <span x-text="tahun"></span>-<span x-text="semester"></span>
                                    </span>
                                    <span class="text-slate-600">
                                        <template x-if="semester == '1'">
                                            <span>
                                                (Genap <span x-text="tahun - 1"></span>/<span x-text="tahun"></span>)
                                            </span>
                                        </template>
                                        <template x-if="semester == '2'">
                                            <span>
                                                (Ganjil <span x-text="tahun"></span>/<span
                                                    x-text="parseInt(tahun) + 1"></span>)
                                            </span>
                                        </template>
                                    </span>
                                </div>
                                <button type="submit"
                                    class="w-full lg:w-auto inline-flex items-center justify-center rounded-lg bg-[#1E3A8A] px-6 py-2.5 text-sm font-medium text-white transition hover:bg-[#152F79]">
                                    Tambah Periode
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-2 shadow-sm mb-3">
                <form method="GET">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400">
                        </i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode periode..."
                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm">
                    </div>
                </form>
                @if(request('search'))
                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $periodes->total() }} periode ditemukan
                            </p>
                            <p class="text-xs text-slate-500">
                                untuk pencarian "{{ request('search') }}"
                            </p>
                        </div>
                        <a href="{{ route('admin.periode.index') }}"
                            class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-200">
                            Reset
                        </a>
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-3 px-4 mb-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 mb-2">
                        <h3 class="text-base font-bold text-slate-800">
                            Daftar Periode
                        </h3>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($periodes as $periode)
                        <div
                            class="group rounded-xl p-2 transition-all duration-300 hover:shadow-md {{ $periode->is_active ? 'border border-[#1E3A8A]/20 bg-[#E0E8FF]/20' : 'border border-slate-100 bg-slate-50/50 hover:border-slate-200 hover:bg-white'}}">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <form action="{{ route('admin.periode.activate', $periode->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="shrink-0 group absolute">
                                            @if($periode->is_active)
                                                <div
                                                    class="flex h-4 w-4 items-center justify-center rounded-full border-2 border-emerald-500">
                                                    <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                                </div>
                                            @else
                                                <div
                                                    class="h-4 w-4 rounded-full border-2 border-slate-300 group-hover:border-emerald-500">
                                                </div>
                                            @endif
                                        </button>
                                    </form>
                                    <div
                                        class="ml-3 shrink-0 flex items-center justify-center w-10 h-10 rounded-xl {{ $periode->is_active ? 'bg-white text-[#1E3A8A]' : 'bg-[#A5BCFF]/20 text-[#1E3A8A]'}}">
                                        <i data-lucide="calendar-range" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-md font-bold text-slate-800">
                                                {{ $periode->kode }}
                                            </p>
                                            @if($periode->is_active)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-emerald-200 px-2 text-[10px] font-bold text-emerald-800">
                                                    AKTIF
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm leading-relaxed text-slate-500">
                                            {{ $periode->label }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">Dibuat
                                            {{ $periode->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end pr-2">
                                    @if($periode->audits_count)
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-[#1E3A8A]/10 px-2.5 py-1 text-[11px] font-semibold text-[#1E3A8A]">
                                                <i data-lucide="clipboard-check" class="w-3 h-3"></i>
                                                {{ $periode->audits_count }} Audit
                                            </span>
                                            <span
                                                class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-600">
                                                Digunakan
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-medium text-amber-600">
                                                Belum Digunakan
                                            </span>
                                            <form method="POST" action="{{ route('admin.periode.destroy', $periode) }}"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-kode="{{ $periode->kode }}"
                                                    class="btn-delete flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 transition">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                            <div class="flex flex-col items-center">
                                <div
                                    class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                    <i data-lucide="calendar-range" class="w-6 h-6"></i>
                                </div>
                                @if(request('search'))
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Periode tidak ditemukan.
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Coba gunakan kata kunci lain.
                                    </p>
                                @else
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Belum ada periode.
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Buat periode audit baru.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-3">
                {{ $periodes->links() }}
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const kode = this.dataset.kode;
                Swal.fire({
                    title: 'Hapus Periode?',
                    text: `Periode ${kode} akan dihapus.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#64748B',
                    customClass: {
                        popup: 'rounded-2xl',
                        title: 'text-slate-800',
                        title: 'text-xl',
                        confirmButton: 'rounded-xl',
                        cancelButton: 'rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>