<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div x-cloak
        x-data="{ openImport: {{ $errors->has('file') ? 'true' : 'false' }}, openUploadPdf: false, reviewMode: @json(request("mode") == "review") }"
        class="pt-[15px] pb-[13px] lg:py-[14px]">
        @if(request('mode') !== 'review')
            <div class="max-w-[1700px] mx-auto">
                <div x-cloak x-data="{ showAuditInfo:false }"
                    class="relative rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-2 pb-3 px-3 lg:pb-3 lg:pt-2 lg:px-3 text-white shadow-md mb-3">
                    <div class="absolute right-0 top-0 opacity-10">
                        <i data-lucide="clipboard-list" class="w-40 h-40"></i>
                    </div>
                    <div class="absolute top-0 left-0 z-20">
                        <a href="{{ route('asesor.audit.index') }}"
                            class="inline-flex items-center justify-center rounded-tl-xl rounded-br-lg px-1.5 py-2.5 text-white hover:bg-white/10">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </a>
                    </div>
                    <div class="absolute top-1.5 right-1.5 z-20">
                        <button @click="showAuditInfo = !showAuditInfo"
                            class="flex h-6 w-6 items-center justify-center rounded-lg hover:backdrop-blur-sm text-white hover:bg-white/20">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </button>
                    </div>
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
                            <div class="flex items-between ml-8 gap-2">
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
                        <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-3">
                            @if($audit->temuan->count() > 0)
                                @if($allReviewed)
                                    <div
                                        class="inline-flex items-center gap-2 rounded-xl bg-green-500/60 backdrop-blur-sm px-4 py-2 text-white shadow-lg">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                        Review Selesai
                                    </div>
                                @else
                                    <a href="{{ route('asesor.audit.show', [$audit->id, 'mode' => 'review']) }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/10 backdrop-blur-sm pl-3 py-2 pr-6 text-white font-medium shadow-lg active:bg-white/30 lg:hover:bg-white/30 transition">
                                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                                        Review Temuan
                                        @if($reviewReady > 0)
                                            <span
                                                class="absolute -top-2 -right-2 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                                                {{ $reviewReady }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @if(session('error'))
                    <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                        class="mb-3 rounded-xl border border-red-200 bg-red-50 py-2 px-2.5">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500">
                            </i>
                            <div>
                                <p class="font-semibold text-red-700">
                                    Import Gagal
                                </p>
                                <p class="text-sm text-red-600">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(session('success'))
                    <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                        class="mb-3 rounded-xl border border-green-200 bg-green-50 py-2 px-2.5">
                        <div class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-500">
                            </i>
                            <div>
                                <p class="font-semibold text-green-700">
                                    Berhasil
                                </p>
                                <p class="text-sm text-green-600">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @php
                    $totalTemuan = $audit->temuan->count();
                    $totalOpen = $audit->temuan->where('status', 'OPEN')->count();
                    $totalClosed = $audit->temuan->where('status', 'CLOSED')->count();
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 mb-3">
                    <div
                        class="col-span-2 lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400 sm:max-w-none">
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
                    <div
                        class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400 max-w-[50px] sm:max-w-none">
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
                    <div
                        class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400 max-w-[50px] sm:max-w-none">
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
                <div class="rounded-xl border border-slate-200 bg-white pt-2 px-3 pb-3 shadow-sm mb-3">
                    <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-slate-800">
                                Import Instrumen
                            </h3>
                            <p class="text-sm text-slate-500">
                                Upload file instrumen audit untuk membagikan temuan.
                            </p>
                        </div>
                        <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                            <a href="{{ route('asesor.audit.template.download', $audit->id) }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Download Template
                            </a>
                            <button @click="openImport = true"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-[#4866BD] px-4 py-2 text-sm font-medium text-white hover:bg-[#1E3A8A]">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Import Excel
                            </button>
                        </div>
                    </div>
                </div>
                {{-- LIST TEMUAN --}}
                <div x-data="{ openTemuan: {{ request('temuan') ? (int) request('temuan') : 'null' }} }"
                    x-init="if(openTemuan){ $nextTick(() => { document .getElementById('temuan-' + openTemuan) ?.scrollIntoView({ behavior: 'smooth', block: 'center' }); }); }"
                    class="space-y-2">
                    <div
                        class="relative flex flex-col lg:items-start lg:flex-row lg:justify-between border-t border-slate-300 pt-2">
                        <div class="pl-1 mb-1">
                            <h3 class="text-lg font-semibold text-slate-800">
                                Daftar Temuan
                            </h3>
                        </div>
                        <div class="flex relative flex-col lg:flex-row gap-2 mb-2">
                            <div class="flex justify-between lg:gap-2">
                                <a href="{{ route('asesor.audit.export', $audit->id) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600">
                                    <i data-lucide="upload" class="w-4 h-4"></i>
                                    Export Excel
                                </a>
                                <button @click="openUploadPdf = true"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    Upload PDF Final
                                </button>
                            </div>
                        </div>
                    </div>
                    @forelse($temuan as $t)
                        <div id="temuan-{{ $t->id }}"
                            class="rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:shadow-xl"
                            :class="openTemuan === {{ $t->id }} ? 'ring-2 ring-[#1E3A8A]/50' : 'border-slate-200'">
                            <button type="button" @click="openTemuan = openTemuan === {{ $t->id }} ? null : {{ $t->id }}"
                                class="flex w-full items-center justify-between px-4 pt-3 pb-2 text-left">
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
                                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform"
                                            :class="{ 'rotate-180': openTemuan === {{ $t->id }} }">
                                        </i>
                                    </div>
                                    <div class="mt-2 border-t border-slate-200 pt-2">
                                        <h4 class="text-[15px] font-semibold text-slate-600"
                                            :class="openTemuan === {{ $t->id }} ? '' : 'line-clamp-1'">
                                            {{ $t->temuan }}
                                        </h4>
                                    </div>
                                </div>
                            </button>
                            <div x-show="openTemuan === {{ $t->id }}" x-transition class="px-4 pb-4">
                                @if($t->tindakan_perbaikan_awal)
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
                                @if($t->status == 'CLOSED' && $t->hasil_ami)
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
                                @if($t->status == 'CLOSED' && $t->tanggapan_auditor)
                                    <div class="mt-2 rounded-lg border border-amber-100 bg-amber-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                                            Tanggapan Auditor 1
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tanggapan_auditor }}
                                        </p>
                                    </div>
                                @endif
                                @if($t->status == 'CLOSED' && $t->tanggapan_auditor_2)
                                    <div class="mt-2 rounded-lg border border-purple-100 bg-purple-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                            Tanggapan Auditor 2
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tanggapan_auditor_2 }}
                                        </p>
                                    </div>
                                @endif
                                @if($t->status == 'OPEN' && ! $t->needs_review)
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
                        <div class="md:col-span-2">
                            <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="clipboard-x" class="w-6 h-6"></i>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Belum Ada Temuan
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Temuan hasil audit akan muncul di sini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="mt-3">
                    {{ $temuan->links() }}
                </div>
            </div>
            <x-ui.modal open="openImport" title="Import Instrumen" maxWidth="max-w-md">
                <form action="{{ route('asesor.audit.import', $audit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-3">
                        <div x-data="{ fileName:'', isDragging:false, dragCounter:0 }">
                            <input x-ref="file" type="file" name="file" required class="hidden"
                                @change="fileName = $event.target.files[0]?.name || '';">
                            <div @click="$refs.file.click()" @dragover.prevent="isDragging = true;"
                                @dragenter.prevent="dragCounter++; isDragging = true;"
                                @dragleave.prevent="dragCounter--; if (dragCounter <= 0) { isDragging = false; dragCounter = 0; }"
                                @drop.prevent="dragCounter = 0; isDragging = false; $refs.file.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name || '';"
                                class="cursor-pointer rounded-xl border-2 border-dashed p-10 text-center transition"
                                :class="isDragging? 'border-[#1E3A8A] bg-[#EEF2FF]': 'border-slate-300 bg-slate-50 hover:border-[#1E3A8A] hover:bg-[#EEF2FF]'">
                                <div class="flex flex-col items-center">
                                    <div class="mb-2 flex h-8 items-center justify-center">
                                        <div class="mb-2">
                                            <i data-lucide="download-cloud" class="w-8 h-8 text-slate-400">
                                            </i>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-slate-700"
                                        x-text="isDragging ? 'Lepaskan File di Sini' : 'Tarik File Excel ke Sini'">
                                    </p>
                                    <p class="text-sm text-slate-500"
                                        x-text="isDragging ? 'File akan diunggah setelah dilepas' : 'atau klik untuk memilih file'">
                                    </p>
                                    <template x-if="fileName">
                                        <div
                                            class="mt-2 inline-flex items-center gap-2 rounded-full bg-[#E0E8FF] px-2 py-1 text-xs font-medium text-[#1E3A8A]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6" />
                                            </svg>
                                            <span x-text="fileName"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            @error('file')
                                <div class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="alert-circle" class="w-3 h-3 text-red-500">
                                        </i>
                                        <span class="text-xs text-red-600">
                                            {{ $message }}
                                        </span>
                                    </div>
                                </div>
                            @enderror
                            <p class="mt-2 text-xs text-slate-400">
                                Format yang didukung: .xlsx, .xls (maks. 5 MB)
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pb-3 px-3">
                        <button type="button" @click="openImport = false"
                            class="rounded-lg px-3 py-2 bg-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-[#1E3A8A] px-3 py-2 text-sm font-medium text-white hover:bg-[#152F79]">
                            Import
                        </button>
                    </div>
                </form>
            </x-ui.modal>
            <x-ui.modal open="openUploadPdf" title="Upload PDF Final PTPP" maxWidth="max-w-md">
                <form action="{{ route('asesor.audit.uploadFinalPdf', $audit->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="p-3">
                        <div x-data="{ fileName:'', isDragging:false, dragCounter:0 }">
                            <input x-ref="file" type="file" name="file" accept=".pdf" required class="hidden"
                                @change="fileName = $event.target.files[0]?.name || '';">
                            <div @click="$refs.file.click()" @dragover.prevent="isDragging = true;"
                                @dragenter.prevent="dragCounter++; isDragging = true;"
                                @dragleave.prevent="dragCounter--; if (dragCounter <= 0) { isDragging = false; dragCounter = 0; }"
                                @drop.prevent="dragCounter = 0; isDragging = false; $refs.file.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name || '';"
                                class="cursor-pointer rounded-xl border-2 border-dashed p-10 text-center transition"
                                :class="isDragging? 'border-[#1E3A8A] bg-[#EEF2FF]': 'border-slate-300 bg-slate-50 hover:border-[#1E3A8A] hover:bg-[#EEF2FF]'">
                                <div class="flex flex-col items-center">
                                    <div class="mb-2 flex h-8 items-center justify-center">
                                        <div class="mb-2">
                                            <i data-lucide="file-check-2" class="w-8 h-8 text-slate-400">
                                            </i>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-slate-700"
                                        x-text="isDragging ? 'Lepaskan File PDF di Sini' : 'Tarik File PDF ke Sini'">
                                    </p>
                                    <p class="text-sm text-slate-500"
                                        x-text="isDragging ? 'File akan diunggah setelah dilepas' : 'atau klik untuk memilih file PDF'">
                                    </p>
                                    <template x-if="fileName">
                                        <div
                                            class="mt-2 inline-flex items-center gap-2 rounded-full bg-[#E0E8FF] px-2 py-1 text-xs font-medium text-[#1E3A8A]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6" />
                                            </svg>
                                            <span x-text="fileName"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            @error('file')
                                <div class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="alert-circle" class="w-3 h-3 text-red-500">
                                        </i>
                                        <span class="text-xs text-red-600">
                                            {{ $message }}
                                        </span>
                                    </div>
                                </div>
                            @enderror
                            <p class="mt-2 text-xs text-slate-400">
                                Format yang didukung: .pdf (maks. 10 MB)
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pb-3 px-3">
                        <button type="button" @click="openUploadPdf = false"
                            class="rounded-lg px-3 py-2 bg-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-[#1E3A8A] px-3 py-2 text-sm font-medium text-white hover:bg-[#152F79]">
                            Upload PDF
                        </button>
                    </div>
                </form>
            </x-ui.modal>
        @endif
        @if(request('mode') === 'review')
            <div x-transition.opacity class="z-[999] overflow-y-auto">
                <div class="mb-3 rounded-xl border border-slate-200 bg-white p-2">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex gap-3">
                            <a href="{{ route('asesor.audit.show', $audit->id) }}"
                                class="inline-flex items-center rounded-l-lg p-1 text-slate-600 hover:bg-slate-200">
                                <i data-lucide="chevron-left" class="h-5 w-5"></i>
                            </a>
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">
                                    Review Temuan
                                </h2>
                                <p class="text-sm text-slate-500">
                                    {{ $audit->nama_audit }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-start justify-center gap-1">
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                                {{ $audit->periode->kode }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ $audit->unit->nama }}
                            </span>
                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                                {{ $reviewTemuan->count() }} OPEN
                            </span>
                        </div>
                    </div>
                </div>
                <div x-data="{ openTemuan: {{ request('temuan', $reviewTemuan->first()?->id ?? 'null') }} }"
                    class="space-y-2">
                    @forelse($reviewTemuan as $t)
                        <div id="temuan-{{ $t->id }}"
                            class="rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1"
                            x-init="if (openTemuan == {{ $t->id }}) { $nextTick(() => { $el.scrollIntoView({behavior: 'smooth', block: 'center'}); }); }">
                            <button type="button" @click="openTemuan = openTemuan === {{ $t->id }} ? null : {{ $t->id }}"
                                class="flex w-full items-center justify-between px-4 pt-3 pb-2 text-left">
                                <div class="flex flex-wrap items-center justify-between gap-2 w-full">
                                    <div>
                                        <span
                                            class="inline-flex items-center rounded-full bg-[#E0E8FF] px-3 py-1 text-xs font-bold text-[#1E3A8A]">
                                            {{ $t->kode_indikator }}
                                        </span>
                                        <span
                                            class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                                            OPEN
                                        </span>
                                        @if($t->audit->auditor_1_id == auth()->id())
                                            @if($t->hasil_ami || $t->tanggapan_auditor)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                                    Draft Tersimpan
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                    <i data-lucide="chevron-down" class="h-5 w-5 text-slate-400 transition-transform"
                                        :class="{ 'rotate-180': openTemuan === {{ $t->id }} }">
                                    </i>
                                    <div class="w-full py-1 border-t border-slate-200">
                                        <h4 class="text-[14px] font-semibold text-slate-600 leading-relaxed"
                                            :class="openTemuan === {{ $t->id }} ? '' : 'line-clamp-1'">
                                            {{ $t->temuan }}
                                        </h4>
                                    </div>
                                </div>
                            </button>
                            <div x-show="openTemuan === {{ $t->id }}" x-transition class="px-2 pb-3">
                                {{-- TINDAKAN --}}
                                <div
                                    class="rounded-lg border border-slate-200 bg-slate-50 py-2 lg:pl-3 lg:pr-2 px-3 relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Tindakan Perbaikan
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700">
                                            {{ $t->tindakan_perbaikan_awal }}
                                        </p>
                                    </div>
                                    {{-- BUKTI --}}
                                    @if($t->bukti_link)
                                        <div class="shrink-0">
                                            <a href="{{ $t->bukti_link }}" target="_blank"
                                                class="inline-flex items-center gap-2 rounded-lg bg-[#E0E8FF] hover:bg-[#1E3A8A] hover:text-white px-3 py-2 text-sm font-medium text-[#1E3A8A]">
                                                <i data-lucide="external_link" class="w-4 h-4"></i>
                                                Lihat Bukti
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    @if($t->tindakan_perbaikan_awal && $t->status == 'OPEN')
                                        @if($t->audit->auditor_1_id == auth()->id())
                                            @if($t->tanggapan_auditor_2)
                                                <div
                                                    class="mt-2 flex items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-2">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                                    <span class="text-sm text-slate-700">
                                                        Auditor 2 telah memberi tanggapan.
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="mt-2 rounded-lg border border-slate-200 bg-slate-100 pt-1 px-2 pb-2">
                                                <form action="/asesor/review/{{ $t->id }}" method="POST">
                                                    @csrf
                                                    <div class="mt-2">
                                                        <label
                                                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                                            Hasil AMI
                                                        </label>
                                                        <textarea name="hasil_ami" rows="4"
                                                            placeholder="Berikan hasil audit mutu internal..."
                                                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">{{ $t->hasil_ami }}</textarea>
                                                    </div>
                                                    <div class="mt-1 mb-1">
                                                        <label
                                                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                                            Tanggapan Auditor 1
                                                        </label>
                                                        <textarea name="tanggapan" rows="4" placeholder="Berikan tanggapan..."
                                                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">{{ $t->tanggapan_auditor }}</textarea>
                                                    </div>
                                                    <div class="mt-1 flex justify-end">
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-700">
                                                            <i data-lucide="save" class="w-4 h-4"></i>
                                                            Simpan Draft
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            @if($t->hasil_ami && $t->tanggapan_auditor)
                                                <form action="/asesor/validasi/{{ $t->id }}" method="POST">
                                                    @csrf
                                                    <div
                                                        class="relative ml-1 mt-2 flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                                                        <div class="w-auto">
                                                            <x-ui.custom-select name="status" :selected="$t->status"
                                                                placeholder="Pilih Status" :options="[['value' => 'OPEN', 'label' => 'OPEN'], ['value' => 'CLOSED', 'label' => 'CLOSED'],]" valueField="value"
                                                                labelField="label" dropup="true" />
                                                        </div>
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white">
                                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                            Finalisasi Temuan
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        @endif
                                        @if($t->audit->auditor_2_id == auth()->id())
                                            @if($t->tanggapan_auditor)
                                                <div
                                                    class="mt-2 flex items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-2">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                                    <span class="text-sm text-slate-700">
                                                        Auditor 1 telah memberikan tanggapan.
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="mt-2 rounded-lg border border-[#E0E8FF] bg-[#F8FAFF] pt-1 px-3 pb-3">
                                                <h5 class="font-semibold text-slate-800">
                                                    Review Auditor
                                                </h5>
                                                <form action="/asesor/review/{{ $t->id }}" method="POST">
                                                    @csrf
                                                    <div class="mt-2 mb-1">
                                                        <label
                                                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                                            Tanggapan Auditor 2
                                                        </label>
                                                        <textarea name="tanggapan" rows="4" placeholder="Berikan tanggapan..."
                                                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">{{ $t->tanggapan_auditor_2 }}</textarea>
                                                    </div>
                                                    <div class="mt-2 relative flex lg:items-end justify-end">
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-2 rounded-xl bg-[#1E3A8A] px-4 py-2.5 text-sm font-medium text-white">
                                                            <i data-lucide="save" class="w-4 h-4"></i>
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2">
                            <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="file-check" class="w-6 h-6"></i>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Temuan Telah Direview
                                    </p>
                                    <p class="mt-1 text-sm text-slate-400">
                                        Temuan berstatus OPEN akan muncul kembali di sini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-app-layout>