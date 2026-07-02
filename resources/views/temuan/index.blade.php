<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="pt-[15px] pb-[13px] lg:py-[14px]">
        <div x-data="{ openTemuan: {{ request('temuan', $temuan->id ?? 'null') }} }" class="max-w-7xl mx-auto">
            <div x-cloak x-data="{ showAuditInfo:false }"
                class="relative rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-2 pb-3 px-3 lg:pb-3 lg:pt-2 lg:px-3 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="clipboard-list" class="w-40 h-40"></i>
                </div>
                <div class="absolute top-0 left-0 z-20">
                    <a href="{{ route('temuan.index') }}"
                        class="inline-flex items-center justify-center rounded-tl-xl rounded-br-lg px-1.5 py-2.5 text-white hover:bg-white/10">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </a>
                </div>
                {{-- INFO BUTTON --}}
                <div class="absolute top-1.5 right-1.5 z-20">
                    <button @click="showAuditInfo = !showAuditInfo"
                        class="flex h-6 w-6 items-center justify-center rounded-lg hover:backdrop-blur-sm text-white hover:bg-white/20">
                        <i data-lucide="info" class="w-4 h-4"></i>
                    </button>
                </div>
                {{-- POPUP INFO --}}
                <div x-show="showAuditInfo" x-transition @click.away="showAuditInfo = false"
                    class="absolute right-1 top-9 z-[970] left-1/2 -translate-x-1/2 md:left-auto md:right-1 md:translate-x-0 w-[92vw] max-w-md rounded-xl border border-slate-200 bg-white/90 backdrop-blur-sm p-3 text-slate-700 shadow-2xl">
                    <h4 class="mb-2 font-semibold text-slate-800">
                        Informasi Audit
                    </h4>
                    <div class="space-y-2 text-sm px-1">
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Nama Audit</span>
                            <span>{{ $audit->nama_audit }}</span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Periode</span>
                            <span>{{ $audit->periode->kode }} {{ $audit->periode->label }}</span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Unit</span>
                            <span>{{ $audit->unit->nama }}</span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Tanggal</span>
                            <span>
                                {{ \Carbon\Carbon::parse($audit->tanggal_audit)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Wakil Auditi</span>
                            <span>{{ $audit->wakilAuditi->name ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Lead Auditor</span>
                            <span>{{ $audit->leadAuditor->name ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Auditor 1</span>
                            <span>{{ $audit->auditor1->name ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-[100px_1fr] gap-3">
                            <span class="text-slate-500">Auditor 2</span>
                            <span>{{ $audit->auditor2->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-3">
                    <div>
                        <div class="flex items-center ml-8 gap-2">
                            <h1 class="text-2xl font-bold text-white">
                                {{ $audit->nama_audit }}
                            </h1>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2 ml-1">
                            <span
                                class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
                                {{ $audit->periode->kode }}
                            </span>
                            <span
                                class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
                                {{ $audit->unit->nama }}
                            </span>
                        </div>
                    </div>
                    @if($totalOpen == 0)
                        <div
                            class="inline-flex items-center gap-3 rounded-xl bg-green-500/60 backdrop-blur-sm px-4 py-2 text-white shadow-lg">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Semua Temuan Selesai
                        </div>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 mb-3">
                {{-- TOTAL --}}
                <div
                    class="col-span-2 lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-slate-400">
                                Total Temuan
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalTemuan }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#E77817]/5 text-[#E77817]">
                            <i data-lucide="file-warning" class="w-5 h-5"></i>
                        </div>
                    </div>
                </div>
                {{-- OPEN --}}
                <div
                    class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-slate-400">
                                Temuan Open
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalOpen }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-600">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        </div>
                    </div>
                </div>
                {{-- CLOSED --}}
                <div
                    class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-slate-400">
                                Temuan Closed
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $totalClosed }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-50 text-green-600">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex items-start justify-between border-t border-slate-300 pt-2">
                    <div class="pl-1">
                        <h3 class="text-lg font-semibold text-slate-800">
                            Daftar Temuan
                        </h3>
                    </div>
                </div>
                @forelse($temuan as $t)
                    @php
                        if ($t->status === 'CLOSED') {
                            $flowIcon = 'check-circle';
                            $flowLabel = 'Selesai';
                            $flowClass = 'bg-green-100 text-green-700';
                        } elseif (! empty($t->tindakan_perbaikan_awal) && $t->needs_review && ! $t->review_finalized) {
                            $flowIcon = 'clock';
                            $flowLabel = 'Menunggu Review';
                            $flowClass = 'bg-amber-100 text-amber-700';
                        } elseif ($t->review_finalized && $t->status === 'OPEN') {
                            $flowIcon = 'rotate-ccw';
                            $flowLabel = 'Perlu Revisi';
                            $flowClass = 'bg-red-100 text-red-700';
                        } else {
                            $flowIcon = 'search';
                            $flowLabel = 'Belum Ditindaklanjuti';
                            $flowClass = 'bg-slate-100 text-slate-700';
                        }
                    @endphp
                    <div id="temuan-{{ $t->id }}"
                        class="rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:shadow-xl"
                        :class="openTemuan === {{ $t->id }} ? 'ring-2 ring-[#1E3A8A]/50' : 'border-slate-200'">
                        <button @click="openTemuan = openTemuan === {{ $t->id }} ? null : {{ $t->id }}"
                            class="flex w-full items-center justify-between px-4 pt-3 pb-2 text-left shadow">
                            <div class="w-full">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-[#E0E8FF] px-3 py-1 text-xs font-bold text-[#1E3A8A]">
                                            {{ $t->kode_indikator }}
                                        </span>
                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $t->status == 'OPEN' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                            {{ $t->status }}
                                        </span>
                                    </div>
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $flowClass }}">
                                            <i data-lucide="{{ $flowIcon }}" class="w-3 h-3"></i>
                                            {{ $flowLabel }}
                                        </span>
                                </div>
                                <div class="mt-2 border-t border-slate-200 pt-2">
                                    <h4 class="text-[15px] font-semibold text-slate-600"
                                        :class="openTemuan === {{ $t->id }} ? '' : 'line-clamp-1'">
                                        {{ $t->temuan }}
                                    </h4>
                                </div>
                                <div class="flex justify-end">
                                    <i data-lucide="chevron-down" class="h-5 w-5 text-slate-400 transition-transform"
                                        :class="{ 'rotate-180': openTemuan === {{ $t->id }} }">
                                    </i>
                                </div>
                            </div>
                        </button>
                        <div x-show="openTemuan === {{ $t->id }}" x-collapse class="px-4 pb-4">
                            @if($t->status == 'CLOSED')
                                <div
                                    class="mt-2 rounded-lg border border-slate-200 bg-slate-50 py-2 px-3 flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Tindakan Perbaikan
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tindakan_perbaikan_awal ?: '-' }}
                                        </p>
                                    </div>
                                    @if($t->bukti_link)
                                        <div class="shrink-0">
                                            <a href="{{ $t->bukti_link }}" target="_blank"
                                                class="inline-flex items-center gap-2 rounded-lg bg-[#E0E8FF] hover:bg-[#1E3A8A] hover:text-white px-3 py-2 text-sm font-medium text-[#1E3A8A]">
                                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                                Lihat Bukti
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($t->review_finalized)
                                @if($t->hasil_ami)
                                    <div class="border-t border-slate-200 mt-2">
                                        <div class="mt-2 rounded-lg border border-blue-100 bg-blue-50 p-3">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">
                                                Hasil AMI
                                            </p>
                                            <p class="mt-1 text-sm text-slate-700">
                                                {{ $t->hasil_ami }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                @if($t->tanggapan_auditor)
                                    <div class="mt-2 rounded-lg border border-amber-100 bg-amber-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                                            Tanggapan Auditor 1
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tanggapan_auditor }}
                                        </p>
                                    </div>
                                @endif
                                {{-- TANGGAPAN AUDITOR 2 --}}
                                @if($t->tanggapan_auditor_2)
                                    <div class="mt-2 rounded-lg border border-purple-100 bg-purple-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                            Tanggapan Auditor 2
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tanggapan_auditor_2 }}
                                        </p>
                                    </div>
                                @endif
                            @endif
                            @if($t->status == 'OPEN' && ! $t->needs_review)
                                <div class="mt-2 rounded-lg border border-slate-200 bg-slate-100 pt-1 px-3 pb-3">
                                    <form action="/temuan/{{ $t->id }}" method="POST">
                                        @csrf
                                        <div class="mt-2">
                                            <label
                                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                                Tindakan Perbaikan
                                            </label>
                                            <textarea name="tindakan" rows="4"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700"
                                                placeholder="Isi tindakan perbaikan...">{{ $t->tindakan_perbaikan_awal }}</textarea>
                                        </div>
                                        <div class="mt-1 mb-1">
                                            <label
                                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                                Link Bukti
                                            </label>
                                            <input type="text" name="bukti" value="{{ $t->bukti_link }}"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700">
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-[#1E3A8A] px-4 py-2.5 text-sm font-medium text-white">
                                                <i data-lucide="save" class="w-4 h-4"></i>
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            @if($t->status == 'OPEN' && $t->needs_review)
                                <div class="mt-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="clock-3" class="w-4 h-4 text-blue-600"></i>
                                        <span class="text-sm font-medium text-blue-700">
                                            Menunggu Review Auditor
                                        </span>
                                    </div>
                                    <div class="mt-3 rounded-lg border border-slate-200 bg-white py-2 px-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Tindakan Perbaikan
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tindakan_perbaikan_awal ?: '-' }}
                                        </p>
                                        @if($t->bukti_link)
                                            <div class="mt-3">
                                                <a href="{{ $t->bukti_link }}" target="_blank"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-[#E0E8FF] px-3 py-2 text-sm font-medium text-[#1E3A8A]">
                                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                                    Lihat Bukti
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($t->hasil_ami)
                                    <div class="border-t border-slate-200 mt-2">
                                        <div class="mt-2 rounded-lg border border-blue-100 bg-blue-50 p-3">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">
                                                Hasil AMI
                                            </p>
                                            <p class="mt-1 text-sm text-slate-700">
                                                {{ $t->hasil_ami }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                @if($t->tanggapan_auditor)
                                    <div class="mt-2 rounded-lg border border-amber-100 bg-amber-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                                            Tanggapan Auditor 1
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tanggapan_auditor }}
                                        </p>
                                    </div>
                                @endif
                                {{-- TANGGAPAN AUDITOR 2 --}}
                                @if($t->tanggapan_auditor_2)
                                    <div class="mt-2 rounded-lg border border-purple-100 bg-purple-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                            Tanggapan Auditor 2
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tanggapan_auditor_2 }}
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="p-0 border-0">
                            <div class="bg-white border rounded-xl p-10 text-center">
                                <p class="text-gray-400 text-sm">
                                    Tidak ada temuan
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $temuan->links() }}
            </div>
        </div>
    </div>
</x-app-layout>