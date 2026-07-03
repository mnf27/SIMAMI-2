<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-[15px] lg:py-[14px] lg:py-[12px]">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <!-- Background Icon -->
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="file-check-2" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">
                    <!-- Left -->
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Hasil Auditor
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Dokumen PTPP Final yang telah disahkan oleh P2MPP dan siap diunduh oleh auditi.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-3 px-4 mb-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 mb-2">
                        <h3 class="text-base font-bold text-slate-800">
                            Daftar Hasil Auditor
                        </h3>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($audits as $audit)
                        <div
                            class="group rounded-xl border border-slate-100 bg-slate-50/50 p-3 transition-all duration-300 hover:border-slate-200 hover:bg-white hover:shadow-md">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#A5BCFF]/20 text-[#1E3A8A]">
                                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">
                                            {{ $audit->nama_audit }}
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-sm text-slate-600">
                                                {{ $audit->periode->kode }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ $audit->periode->label }}
                                            </p>
                                        </div>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-xs text-slate-500">
                                                <span class="font-medium text-slate-600">Unit</span>
                                                • {{ $audit->unit->nama }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                <span class="font-medium text-slate-600">Lead Auditor</span>
                                                • {{ optional($audit->leadAuditor)->name }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                <span class="font-medium text-slate-600">PDF Diunggah</span>
                                                • {{ $audit->updated_at->translatedFormat('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('auditi.hasil-auditor.download', $audit) }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-[#1E3A8A] px-3 py-2 text-sm font-medium text-white hover:bg-[#152F79] transition">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                            <div class="flex flex-col items-center">
                                <div
                                    class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                    <i data-lucide="file-check-2" class="w-6 h-6"></i>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    Belum ada dokumen hasil auditor.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Dokumen PDF final akan muncul setelah diunggah oleh asesor.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-3">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
</x-app-layout>