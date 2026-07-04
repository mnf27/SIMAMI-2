<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div x-cloak x-data="{ openCreateAudit:false }" class="pt-[15px] pb-[13px] lg:py-[14px]">
        <div class="max-w-[1700px] mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-3 px-4 lg:pl-5 lg:py-3 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="clipboard-check" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Temuan
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Kelola dan pantau tindak lanjut temuan audit yang menjadi tanggung jawab Anda.
                        </p>
                    </div>
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
            <div class="bg-white border border-slate-200 rounded-xl p-1 mb-2 flex gap-1">
                <a href="{{ route('temuan.index') }}"
                    class="flex-1 text-sm text-center py-1 rounded-lg transition {{ ! request('status') ? 'bg-[#1E3A8A] text-white font-medium' : 'text-slate-600 hover:bg-slate-50' }}">
                    Semua </a>
                <a href="{{ route('temuan.index', ['status' => 'OPEN']) }}"
                    class="flex-1 text-sm text-center py-1 rounded-lg transition {{ request('status') == 'OPEN' ? 'bg-[#1E3A8A] text-white font-medium' : 'text-slate-600 hover:bg-slate-50' }}">
                    Open </a>
                <a href="{{ route('temuan.index', ['status' => 'CLOSED']) }}"
                    class="flex-1 text-sm text-center py-1 rounded-lg transition {{ request('status') == 'CLOSED' ? 'bg-[#1E3A8A] text-white font-medium' : 'text-slate-600 hover:bg-slate-50' }}">
                    Closed </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                {{-- LIST AUDIT --}}
                @forelse($audits as $audit)
                    @php
                        $singleItem = $audits->count() === 1;
                        $isLastOdd =
                            $audits->count() % 2 !== 0 &&
                            $loop->last &&
                            $audits->count() > 1;
                        $userTemuan = $audit->temuan
                            ->filter(fn ($t) => $t->users->contains(auth()->id()));
                        if (request('status')) {
                            $userTemuan = $userTemuan
                                ->where('status', request('status'));
                        }
                        $openCount = $userTemuan->where('status', 'OPEN')->count();
                        $closedCount = $userTemuan->where('status', 'CLOSED')->count();
                        $totalTemuan = $userTemuan->count();
                        $progress = $totalTemuan > 0
                            ? round(($closedCount / $totalTemuan) * 100)
                            : 0;
                        $progressColor =
                            $progress >= 80 ? 'bg-green-500' :
                            ($progress >= 50 ? 'bg-amber-500' : 'bg-red-500');
                    @endphp
                    <a href="{{ route('temuan.index', ['audit_id' => $audit->id]) }}"
                        class="group rounded-xl border border-slate-200 bg-white px-4 pt-4 pb-2 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ ($singleItem || $isLastOdd) ? 'md:col-span-2' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <!-- ICON -->
                                <div
                                    class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A]">
                                    <i data-lucide="file-check" class="w-5 h-5"></i>
                                </div>
                                <!-- CONTENT -->
                                <div>
                                    <div class="flex items-center gap-2">
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
                        <div class="flex-1"></div>
                        <div class="mt-2 pt-2 border-t border-slate-200 flex items-center justify-between">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-bold text-slate-800"> {{ $totalTemuan }} </p>
                                <p class="text-xs text-slate-500"> Temuan </p>
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
                                    Belum ada temuan audit.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Temuan yang menjadi tanggung jawab Anda akan muncul di sini.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
</x-app-layout>