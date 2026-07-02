<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div x-cloak x-data="{ openCreateAudit:false }" class="pt-[15px] pb-[13px] lg:py-[14px]">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-3 px-4 lg:pl-5 lg:py-3 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="clipboard-check" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Audit
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Kelola dan pantau seluruh aktivitas audit mutu internal.
                        </p>
                    </div>
                    <button type="button" @click="openCreateAudit = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/10 backdrop-blur-sm pl-3 py-2 pr-6 text-white font-medium shadow-lg active:bg-white/30 lg:hover:bg-white/30 transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Buat Audit
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-slate-400 max-w-[50px] sm:max-w-none">
                                Total Audit
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalAudit }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#1E3A8A]/5 text-[#1E3A8A]">
                            <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-wide text-slate-400 max-w-[50px] sm:max-w-none">
                                Total Temuan
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalTemuan }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#E77817]/5 text-[#E77817]">
                            <i data-lucide="file-warning" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-wide text-slate-400 max-w-[50px] sm:max-w-none">
                                Temuan Open
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalOpen }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-red-50 text-red-600">
                            <i data-lucide="alert-circle" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-wide text-slate-400 max-w-[50px] sm:max-w-none">
                                Temuan Closed
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalClosed }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-green-50 text-green-600">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm py-3 px-4 mb-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <h3 class="text-base font-bold text-slate-800">
                            Filter Audit
                        </h3>
                    </div>
                    <div class="flex items-end mt-1">
                        <a href="{{ route('asesor.audit.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 p-2 text-slate-600 hover:bg-slate-50 transition">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 pb-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Unit
                        </label>
                        @php
                            $unitOptions = collect([
                                (object) [
                                    'id' => '',
                                    'nama' => 'Semua Unit'
                                ]
                            ])->concat($units);
                        @endphp
                        <x-ui.custom-select name="unit_id" placeholder="Semua Unit" :options="$unitOptions"
                            valueField="id" labelField="nama" :selected="request('unit_id')"
                            onSelect="$nextTick(() => $el.closest('form').submit())" :autoSubmit="true" />
                    </div>
                    {{-- STATUS --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Status
                        </label>
                        @php
                            $statusOptions = [
                                (object) [
                                    'id' => '',
                                    'nama' => 'Semua Status'
                                ],
                                (object) [
                                    'id' => 'OPEN',
                                    'nama' => 'OPEN'
                                ],
                                (object) [
                                    'id' => 'CLOSED',
                                    'nama' => 'CLOSED'
                                ]
                            ];
                        @endphp
                        <x-ui.custom-select name="status" placeholder="Semua Status" :options="$statusOptions"
                            valueField="id" labelField="nama" :selected="request('status')"
                            onSelect="$nextTick(() => $el.closest('form').submit())" :autoSubmit="true" />
                    </div>
                </div>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                {{-- LIST AUDIT --}}
                @forelse($audits as $audit)
                    @php
                        $singleItem = $audits->count() === 1;
                        $isLastOdd =
                            $audits->count() % 2 !== 0 &&
                            $loop->last &&
                            $audits->count() > 1;
                    @endphp
                    <a href="{{ route('asesor.audit.show', $audit->id) }}"
                        class="group rounded-xl border border-slate-200 bg-white px-4 pt-4 pb-2 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ ($singleItem || $isLastOdd) ? 'md:col-span-2' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <!-- ICON -->
                                <div
                                    class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A]">
                                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                </div>
                                <!-- CONTENT -->
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-[#E0E8FF] px-2.5 py-1 text-[11px] font-bold text-[#1E3A8A]">
                                            {{ $audit->periode->kode }}
                                        </span>
                                    </div>
                                    {{-- NAMA AUDIT --}}
                                    <h3 class="mt-2 text-base font-bold text-slate-800 line-clamp-1">
                                        {{ $audit->nama_audit }}
                                    </h3>
                                    {{-- UNIT --}}
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $audit->unit->nama ?? '-' }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-2 text-xs text-slate-400">
                                        <i data-lucide="calendar-days" class="w-3 h-3"></i>
                                        {{ \Carbon\Carbon::parse($audit->tanggal_audit)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            <!-- ARROW -->
                            <i data-lucide="arrow-up-right"
                                class="w-4 h-4 text-slate-300 transition-all duration-300 group-hover:text-[#152F79] group-hover:translate-x-1 group-hover:-translate-y-1">
                            </i>
                        </div>
                        @php
                            $openCount = $audit->temuan->where('status', 'OPEN')->count();
                            $closedCount = $audit->temuan->where('status', 'CLOSED')->count();
                            $totalTemuan = $openCount + $closedCount;
                            $progress = $totalTemuan > 0
                                ? round(($closedCount / $totalTemuan) * 100)
                                : 0;
                            $progressColor =
                                $progress >= 80 ? 'bg-green-500' :
                                ($progress >= 50 ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <div class="mt-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-slate-500">
                                    Progress
                                </span>
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $progress }}%
                                </span>
                            </div>
                            <div class="h-1 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $progressColor }}" style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-slate-200 flex items-center justify-between">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-bold text-slate-800"> {{ $audit->temuan->count() }} </p>
                                <p class="text-xs text-slate-500"> Temuan </p>
                            </div>
                            @php
                                $openCount = $audit->temuan->where('status', 'OPEN')->count();
                                $closedCount = $audit->temuan->where('status', 'CLOSED')->count();
                            @endphp
                            <div class="flex flex-wrap items-center gap-2">
                                @if($openCount > 0)
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-[11px] font-bold text-red-600">
                                        OPEN {{ $openCount }}
                                    </span>
                                @endif
                                @if($closedCount > 0)
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-[11px] font-bold text-green-600">
                                        CLOSED {{ $closedCount }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2">
                        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                            <div class="flex flex-col items-center">
                                <div
                                    class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                    <i data-lucide="inbox" class="w-6 h-6"></i>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    Belum ada data audit.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Silahkan buat audit baru.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="mt-3">
                {{ $audits->links() }}
            </div>
        </div>
        <x-ui.modal open="openCreateAudit" title="Buat Audit" maxWidth="max-w-5xl">
            <form action="{{ route('asesor.audit.store') }}" method="POST">
                @csrf
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        {{-- NAMA AUDIT --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Nama Audit
                            </label>
                            <input type="text" name="nama_audit" value="{{ old('nama_audit') }}"
                                placeholder="Contoh: Audit Internal Prodi D3 TI"
                                class="w-full rounded-xl border-slate-200 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">
                        </div>
                        {{-- PERIODE --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Periode
                            </label>
                            <x-ui.custom-select name="periode_id" placeholder="Pilih Periode" :options="$periodes"
                                valueField="id" labelField="display" />
                        </div>
                        {{-- UNIT --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Unit
                            </label>
                            <x-ui.custom-select name="unit_id" placeholder="Pilih Unit" :options="$units"
                                valueField="id" labelField="nama" />
                        </div>
                        {{-- WAKIL AUDITI --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Wakil Auditi
                            </label>
                            <x-ui.custom-select name="wakil_auditi_id" placeholder="Pilih Wakil Auditi"
                                :options="$users" valueField="id" labelField="name" />
                        </div>
                        {{-- LEAD AUDITOR --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Lead Auditor
                            </label>
                            <x-ui.custom-select name="lead_auditor_id" placeholder="Pilih Lead Auditor"
                                :options="$asesor" valueField="id" labelField="name" />
                        </div>
                        {{-- AUDITOR 1 --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Auditor 1
                            </label>
                            <x-ui.custom-select name="auditor_1_id" placeholder="Pilih Auditor 1" :options="$asesor"
                                valueField="id" labelField="name" dropup="true" />
                        </div>
                        {{-- AUDITOR 2 --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Auditor 2
                            </label>
                            <x-ui.custom-select name="auditor_2_id" placeholder="Pilih Auditor 2" :options="$asesor"
                                valueField="id" labelField="name" dropup="true" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-200 p-5">
                    <button type="button" @click="openCreateAudit = false"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-[#1E3A8A] px-4 py-2 text-sm font-medium text-white hover:bg-[#152F79]">
                        Buat Audit
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-app-layout>