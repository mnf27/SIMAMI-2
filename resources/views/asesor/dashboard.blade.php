<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-[15px] lg:py-[14px]">
        <div class="max-w-[1700px] mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="layout-dashboard" class="w-40 h-40"></i>
                </div>
                <!-- Content -->
                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">
                    <!-- LEFT -->
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Dashboard Auditor
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm lg:text-base leading-relaxed">
                            Pantau progres audit, tindak lanjut temuan,
                            dan aktivitas audit mutu internal secara real-time.
                        </p>
                    </div>
                    <!-- RIGHT -->
                    <div class="flex items-center">
                        <!-- Progress Mini -->
                        <div
                            class="rounded-2xl bg-white/10 backdrop-blur-sm px-5 py-2 min-w-full lg:min-w-[300px] transition-all duration-300 shadow-lg hover:-translate-y-1 hover:shadow-xl">
                            <p class="text-xs text-blue-100 font-medium">
                                Progress
                            </p>
                            <div class="mt-1 flex items-end gap-2">
                                <h3 class="text-3xl font-bold text-[#6EFF53]">
                                    {{ $persentaseClosed }}%
                                </h3>
                                <span class="text-xs text-blue-100 mb-1">
                                    selesai
                                </span>
                            </div>
                            <div class="mt-2 h-2 bg-white/20 rounded-lg overflow-hidden">
                                <div class="h-full bg-white rounded-lg" style="width: {{ $persentaseClosed }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- STATISTIC CARDS --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                {{-- TOTAL AUDIT --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <!-- Glow -->
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-[#1E3A8A]/30 rounded-full blur-3xl opacity-70">
                    </div>
                    <div class="relative">
                        <!-- Top -->
                        <div class="flex items-start justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase text-slate-400 leading-tight max-w-[50px] sm:max-w-none">
                                    Total Audit
                                </p>
                                <h3 class="mt-2 text-4xl font-bold text-slate-800">
                                    {{ $totalAudit }}
                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-[#1E3A8A]/5 text-[#1E3A8A] transition duration-300 group-hover:scale-110">
                                <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- TOTAL TEMUAN --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-[#EA7A17]/50 rounded-full blur-3xl opacity-50">
                    </div>
                    <div class="relative">
                        <!-- Top -->
                        <div class="flex items-start justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold text-slate-400 uppercase leading-tight max-w-[50px] sm:max-w-none">
                                    Total Temuan
                                </p>
                                <h3 class="mt-2 text-4xl font-bold text-slate-800">
                                    {{ $totalTemuan }}
                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-[#E77817]/5 text-[#E77817] transition duration-300 group-hover:scale-110">
                                <i data-lucide="file-warning" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- OPEN --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white px-4 pt-4 pb-2 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-red-200 rounded-full blur-3xl opacity-70">
                    </div>
                    <div class="relative">
                        <!-- Top -->
                        <div class="flex items-start justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold text-slate-400 uppercase leading-tight max-w-[50px] sm:max-w-none">
                                    Temuan Open
                                </p>
                                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                                    {{ $totalOpen }}
                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-red-50 text-red-500 transition duration-300 group-hover:scale-110">
                                <i data-lucide="alert-circle" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="mt-1 flex flex-col items-start sm:flex-row sm:items-center sm:justify-between">
                            <div
                                class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold {{ $trendOpen > 0 ? 'bg-red-50 text-red-600' : ($trendOpen < 0 ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500') }}">
                                @if($trendOpen > 0)
                                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                                    +{{ $trendOpen }}
                                @elseif($trendOpen < 0)
                                    <i data-lucide="trending-down" class="w-3 h-3"></i>
                                    -{{ abs($trendOpen) }}
                                @else
                                    <i data-lucide="minus" class="w-3 h-3"></i>
                                    0
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                dibanding periode lalu
                            </p>
                        </div>
                    </div>
                </div>
                {{-- CLOSED --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white px-4 pt-4 pb-2 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-green-100 rounded-full blur-3xl opacity-70">
                    </div>
                    <div class="relative">
                        <!-- Top -->
                        <div class="flex items-start justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold text-slate-400 uppercase leading-tight max-w-[50px] sm:max-w-none">
                                    Temuan Closed
                                </p>
                                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                                    {{ $totalClosed }}
                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-green-50 text-green-500 transition duration-300 group-hover:scale-110">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <!-- Bottom -->
                        <div class="mt-1 flex flex-col items-start sm:flex-row sm:items-center sm:justify-between">
                            <div
                                class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold {{ $trendClosed > 0 ? 'bg-green-50 text-green-600' : ($trendClosed < 0 ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-500') }}">
                                @if($trendClosed > 0)
                                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                                    +{{ $trendClosed }}
                                @elseif($trendClosed < 0)
                                    <i data-lucide="trending-down" class="w-3 h-3"></i>
                                    -{{ abs($trendClosed) }}
                                @else
                                    <i data-lucide="minus" class="w-3 h-3"></i>
                                    0
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                dibanding periode lalu
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-3">
                <!-- Chart -->
                <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white py-4 px-5 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Trend Temuan Audit
                            </h3>
                            <p class="text-sm text-slate-500">
                                Perkembangan OPEN dan CLOSED antar periode audit
                            </p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button id="toggleOpen"
                                class="legend-btn active flex items-center gap-2 rounded-2xl bg-[#F6F8FF] px-3 py-1 transition-all duration-200 hover:bg-red-50">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <span class="text-xs font-medium text-slate-500">
                                    OPEN
                                </span>
                            </button>
                            <button id="toggleClosed"
                                class="legend-btn active flex items-center gap-2 rounded-2xl bg-[#F6F8FF] px-3 py-1 transition-all duration-200 hover:bg-green-50">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-xs font-medium text-slate-500">
                                    CLOSED
                                </span>
                            </button>
                        </div>
                    </div>
                    <!-- Fake Chart -->
                    <div class="relative h-[260px] sm:h-[320px] lg:h-[360px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                {{-- TEMUAN TERBARU --}}
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white py-4 px-5 shadow-sm">
                    <!-- HEADER -->
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Temuan Terbaru
                            </h3>
                            <p class="text-sm text-slate-500">
                                Aktivitas temuan audit terbaru
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#EA7A17]/10 text-[#EA7A17]">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <!-- LIST -->
                    <div class="space-y-2">
                        @forelse ($temuanTerbaru as $temuan)
                            <a href="{{ route('asesor.audit.show', $temuan->audit) }}?temuan={{ $temuan->id }}"
                                class="group flex items-start justify-between rounded-2xl border border-slate-100 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-200 hover:bg-white hover:shadow-lg">
                                <!-- LEFT -->
                                <div class="flex items-start gap-3">
                                    <!-- ICON -->
                                    <div
                                        class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl {{ $temuan->status == 'OPEN' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }}">
                                        <i data-lucide="{{ $temuan->status == 'OPEN' ? 'alert-circle' : 'check-circle-2' }}"
                                            class="w-5 h-5">
                                        </i>
                                    </div>
                                    <!-- CONTENT -->
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <p class="text-sm font-bold text-slate-800">
                                                {{ $temuan->kode_indikator }}
                                            </p>
                                            <span
                                                class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-semibold {{ $temuan->status == 'OPEN' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                                {{ $temuan->status }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                                            {{ Str::limit($temuan->temuan, 70) }}
                                        </p>
                                        <div class="mt-2 flex items-center gap-1 text-xs text-slate-400">
                                            <i data-lucide="clock-3" class="w-3 h-3"></i>
                                            {{ $temuan->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <!-- RIGHT -->
                                <div
                                    class="opacity-0 transition-all duration-300 group-hover:translate-x-1 group-hover:opacity-100">
                                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-300"></i>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 p-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Belum ada aktivitas terbaru
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- AUDIT TERBARU --}}
                <div class="lg:col-span-1 rounded-2xl border border-slate-200 bg-white py-4 px-5 shadow-sm">
                    <!-- HEADER -->
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Audit Terbaru
                            </h3>
                            <p class="text-sm text-slate-500">
                                Audit terbaru yang dibuat
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#1E3A8A]/10 text-[#1E3A8A]">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <!-- LIST -->
                    <div class="space-y-2">
                        @forelse ($auditTerbaru as $audit)
                            <a href="{{ route('asesor.audit.show', $audit->id) }}"
                                class="group flex items-start justify-between rounded-2xl border border-slate-100 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-200 hover:bg-white hover:shadow-lg">
                                <div class="flex items-start gap-3">
                                    <!-- ICON -->
                                    <div
                                        class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A]">
                                        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                                    </div>
                                    <!-- CONTENT -->
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ $audit->unit->nama ?? '-' }}
                                        </p>
                                        <div class="mt-1 flex items-center justify-between">
                                            <span
                                                class="inline-flex items-center rounded-full bg-[#E0E8FF] px-2 py-1 text-[11px] font-bold text-[#1E3A8A]">
                                                {{ $audit->periode->kode }}
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-1 text-xs text-slate-400">
                                            <i data-lucide="clock-3" class="w-3 h-3"></i>
                                            {{ $audit->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 p-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Belum ada audit terbaru
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        const gradientRed = ctxTrend.createLinearGradient(0, 0, 0, 300);
        gradientRed.addColorStop(0, 'rgba(239, 68, 68, 0.25)');
        gradientRed.addColorStop(1, 'rgba(239, 68, 68, 0)');
        const gradientGreen = ctxTrend.createLinearGradient(0, 0, 0, 300);
        gradientGreen.addColorStop(0, 'rgba(34, 197, 94, 0.25)');
        gradientGreen.addColorStop(1, 'rgba(34, 197, 94, 0)');
        const trendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: @json($trendData),
                datasets: [
                    {
                        label: 'OPEN',
                        data: @json($openTrend),
                        borderColor: '#EF4444',
                        backgroundColor: gradientRed,
                        fill: true,
                        pointBackgroundColor: '#EF4444',
                    },
                    {
                        label: 'CLOSED',
                        data: @json($closedTrend),
                        borderColor: '#22C55E',
                        backgroundColor: gradientGreen,
                        fill: true,
                        pointBackgroundColor: '#22C55E',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#000000b9',
                        padding: 9,
                        titleFont: {
                            size: 12,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 11
                        },
                        displayColors: true,
                        cornerRadius: 4,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748B',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false
                        },
                        grid: {
                            color: '#EEF2FF'
                        },
                        ticks: {
                            color: '#94A3B8',
                            font: {
                                size: 11
                            },
                            padding: 5
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4,
                        borderWidth: 3
                    },
                    point: {
                        radius: 3,
                        hoverRadius: 4,
                        backgroundColor: '#fff'
                    }
                }
            }
        });
        document
            .getElementById('toggleOpen')
            .addEventListener('click', () => {
                const isVisible = trendChart.isDatasetVisible(0);
                trendChart.setDatasetVisibility(0, !isVisible);
                trendChart.update();
                document
                    .getElementById('toggleOpen')
                    .classList.toggle('inactive', isVisible);
            });
        document
            .getElementById('toggleClosed')
            .addEventListener('click', () => {
                const isVisible = trendChart.isDatasetVisible(1);
                trendChart.setDatasetVisibility(1, !isVisible);
                trendChart.update();
                document
                    .getElementById('toggleClosed')
                    .classList.toggle('inactive', isVisible);
            });
    </script>
    <style>
        .legend-btn.inactive {
            opacity: .45;
            transform: scale(.97);
            background: #F1F5F9;
        }

        .legend-btn.inactive span {
            text-decoration: line-through;
        }
    </style>
</x-app-layout>