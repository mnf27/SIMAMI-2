<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="pt-[15px] pb-[13px] lg:py-[14px]">
        <div x-data="{ openTemuan: {{ request('temuan') ? (int) request('temuan') : 'null' }} }"
            x-init="if(openTemuan){ $nextTick(() => { document.getElementById('temuan-' + openTemuan) ?.scrollIntoView({ behavior:'smooth', block:'center' }); }); }"
            class="max-w-[1700px] mx-auto">
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
                            <p class="text-white/80 mt-1">
                                Distribusikan temuan kepada penanggung jawab.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="badge"> {{ $totalTemuan }} Temuan </span>

                            <span class="badge bg-green"> {{ $sudahDistribusi }} Terdistribusi </span>

                            <span class="badge bg-amber"> {{ $belumDistribusi }} Belum Distribusi </span>
                        </div>
                    </div>

                    @if($belumDistribusi == 0)
                        <div class="inline-flex items-center gap-3 rounded-xl bg-green-500/60">
                            <i data-lucide="check-circle"></i>

                            Distribusi Selesai
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-2">
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
                            @php
                                if ($t->users->isEmpty()) {
                                    $flowIcon = 'users';
                                    $flowLabel = 'Belum Didistribusikan';
                                    $flowClass = 'bg-amber-100 text-amber-700';
                                } else {
                                    $flowIcon = 'check-circle';
                                    $flowLabel = 'Sudah Didistribusikan';
                                    $flowClass = 'bg-green-100 text-green-700';
                                }
                            @endphp

                            <form action="{{ route('temuan.assign', $t->id) }}" method="POST">
                                @csrf

                                <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Penanggung Jawab
                                    </p>

                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($users as $user)
                                            <label
                                                class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 cursor-pointer hover:border-[#1E3A8A]">
                                                <input type="checkbox" name="users[]" value="{{ $user->id }}" {{ $t->users->contains($user->id) ? 'checked' : '' }}
                                                    class="rounded border-slate-300 text-[#1E3A8A]">

                                                <div class="mt-3 rounded-lg bg-blue-50 border border-blue-100 p-3">
                                                    <p class="text-sm text-slate-700">
                                                        Pilih satu atau lebih pengguna yang bertanggung jawab terhadap temuan
                                                        ini.
                                                    </p>
                                                </div>

                                                <div>
                                                    <p class="font-semibold text-slate-700"> {{ $user->name }} </p>

                                                    <p class="text-xs text-slate-500">
                                                        {{ $user->role->nama }}
                                                    </p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-5 flex justify-end">
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-[#1E3A8A] px-4 py-2 text-sm text-white">
                                            <i data-lucide="save" class="w-4 h-4"></i>

                                            Simpan Distribusi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="p-0 border-0">
                            <div class="bg-white border rounded-xl p-10 text-center">
                                <p class="text-gray-400 text-sm"> Tidak ada temuan </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </div>

            <div class="mt-4"> {{ $temuan->links() }} </div>
        </div>
    </div>
</x-app-layout>