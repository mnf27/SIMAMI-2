<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Periode;
use App\Models\TemuanAudit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function filterPeriode($query, $periode)
    {
        return $query->whereHas(
            'periode',
            function ($sub) use ($periode) {
                $sub->where('kode', $periode);
            }
        );
    }

    public function index()
    {
        $periode = session('periode');
        $periodeSebelumnya = null;
        if ($periode) {
            [$tahun, $semester] = explode('-', $periode);
            if ($semester == 1) {
                $periodeSebelumnya =
                    ($tahun - 1).'-2';
            } else {
                $periodeSebelumnya =
                    $tahun.'-1';
            }
        }
        $totalAudit = Audit::when(
            $periode,
            function ($q) use ($periode) {
                $this->filterPeriode($q, $periode);
            }
        )->count();
        $totalTemuan = TemuanAudit::when(
            $periode,
            function ($query) use ($periode) {
                $query->whereHas(
                    'audit',
                    function ($q) use ($periode) {
                        $this->filterPeriode($q, $periode);
                    }
                );
            }
        )->count();
        $totalOpen = TemuanAudit::when(
            $periode,
            function ($query) use ($periode) {
                $query->whereHas(
                    'audit',
                    function ($q) use ($periode) {
                        $this->filterPeriode($q, $periode);
                    }
                );
            }
        )
            ->where('status', 'OPEN')
            ->count();
        $totalClosed = TemuanAudit::when(
            $periode,
            function ($query) use ($periode) {
                $query->whereHas(
                    'audit',
                    function ($q) use ($periode) {
                        $this->filterPeriode($q, $periode);
                    }
                );
            }
        )
            ->where('status', 'CLOSED')
            ->count();
        $totalOpenLalu = TemuanAudit::when(
            $periodeSebelumnya,
            function ($query) use ($periodeSebelumnya) {
                $query->whereHas(
                    'audit',
                    function ($q) use ($periodeSebelumnya) {
                        $q->whereHas(
                            'periode',
                            function ($sub) use ($periodeSebelumnya) {
                                $sub->where(
                                    'kode',
                                    $periodeSebelumnya
                                );
                            }
                        );
                    }
                );
            }
        )
            ->where('status', 'OPEN')
            ->count();
        $totalClosedLalu = TemuanAudit::when(
            $periodeSebelumnya,
            function ($query) use ($periodeSebelumnya) {
                $query->whereHas(
                    'audit',
                    function ($q) use ($periodeSebelumnya) {
                        $q->whereHas(
                            'periode',
                            function ($sub) use ($periodeSebelumnya) {
                                $sub->where(
                                    'kode',
                                    $periodeSebelumnya
                                );
                            }
                        );
                    }
                );
            }
        )
            ->where('status', 'CLOSED')
            ->count();
        $trendOpen = $totalOpen - $totalOpenLalu;
        $trendClosed = $totalClosed - $totalClosedLalu;
        $persentaseClosed =
            $totalTemuan > 0
            ? round(($totalClosed / $totalTemuan) * 100)
            : 0;
        $temuanTerbaru = TemuanAudit::when(
            $periode,
            function ($query) use ($periode) {
                $query->whereHas(
                    'audit',
                    function ($q) use ($periode) {
                        $this->filterPeriode($q, $periode);
                    }
                );
            }
        )
            ->latest()
            ->take(5)
            ->get();
        $auditTerbaru = Audit::when(
            $periode,
            function ($q) use ($periode) {
                $this->filterPeriode($q, $periode);
            }
        )
            ->latest()
            ->take(5)
            ->get();
        $trendData = Periode::whereHas('audits')
            ->orderBy('kode')
            ->pluck('kode');
        $openTrend = [];
        $closedTrend = [];
        foreach ($trendData as $periodeTrend) {
            $openTrend[] = TemuanAudit::whereHas(
                'audit',
                function ($q) use ($periodeTrend) {
                    $q->whereHas(
                        'periode',
                        function ($sub) use ($periodeTrend) {
                            $sub->where('kode', $periodeTrend);
                        }
                    );
                }
            )
                ->where('status', 'OPEN')
                ->count();
            $closedTrend[] = TemuanAudit::whereHas(
                'audit',
                function ($q) use ($periodeTrend) {
                    $q->whereHas(
                        'periode',
                        function ($sub) use ($periodeTrend) {
                            $sub->where(
                                'kode',
                                $periodeTrend
                            );
                        }
                    );
                }
            )
                ->where('status', 'CLOSED')
                ->count();
        }

        return view(
            'asesor.dashboard',
            compact(
                'totalAudit',
                'totalTemuan',
                'totalOpen',
                'totalClosed',
                'trendOpen',
                'trendClosed',
                'persentaseClosed',
                'temuanTerbaru',
                'auditTerbaru',
                'trendData',
                'openTrend',
                'closedTrend',
            )
        );
    }
}
