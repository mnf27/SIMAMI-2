<?php

namespace App\Http\Controllers\Auditi;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $periode = session('periode');
        $baseQuery = $user->temuanAudits()
            ->when(
                $periode,
                function ($query) use ($periode) {
                    $query->whereHas(
                        'audit',
                        function ($q) use ($periode) {
                            $q->whereHas(
                                'periode',
                                function ($sub) use ($periode) {
                                    $sub->where('kode', $periode);
                                }
                            );
                        }
                    );
                }
            );
        $total = (clone $baseQuery)->count();
        $open = (clone $baseQuery)
            ->where('status', 'OPEN')
            ->count();
        $closed = (clone $baseQuery)
            ->where('status', 'CLOSED')
            ->count();
        $totalAudit = (clone $baseQuery)
            ->pluck('audit_id')
            ->unique()
            ->count();
        $persentaseClosed = $total > 0
            ? round(($closed / $total) * 100)
            : 0;
        $temuanTerbaru = $user->temuanAudits()
            ->with('audit.unit')
            ->when(
                $periode,
                function ($query) use ($periode) {
                    $query->whereHas(
                        'audit.periode',
                        function ($q) use ($periode) {
                            $q->where('kode', $periode);
                        }
                    );
                }
            )
            ->latest()
            ->take(5)
            ->get();
        $progressAudit = $user->temuanAudits()
            ->with('audit')
            ->when(
                $periode,
                function ($query) use ($periode) {
                    $query->whereHas(
                        'audit.periode',
                        function ($q) use ($periode) {
                            $q->where('kode', $periode);
                        }
                    );
                }
            )
            ->get()
            ->groupBy('audit_id')
            ->map(function ($temuan) {
                $audit = $temuan->first()->audit;
                $total = $temuan->count();
                $closed = $temuan
                    ->where('status', 'CLOSED')
                    ->count();
                return [
                    'audit_id' => $audit->id,
                    'nama_audit' => $audit->nama_audit,
                    'total' => $total,
                    'closed' => $closed,
                    'open' => $total - $closed,
                    'progress' => $total > 0
                        ? round(($closed / $total) * 100)
                        : 0,
                ];
            });
        $auditPerhatian = collect($progressAudit)
            ->filter(fn ($audit) => $audit['open'] > 0)
            ->sortByDesc('open')
            ->take(5)
            ->values();
        return view(
            'auditi.dashboard',
            compact(
                'totalAudit',
                'total',
                'open',
                'closed',
                'persentaseClosed',
                'temuanTerbaru',
                'progressAudit',
                'auditPerhatian'
            )
        );
    }
}