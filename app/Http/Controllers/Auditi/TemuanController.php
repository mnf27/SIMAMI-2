<?php

namespace App\Http\Controllers\Auditi;

use App\Http\Controllers\Controller;
use App\Models\TemuanAudit;
use Illuminate\Http\Request;
use App\Notifications\TemuanNotification;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class TemuanController extends Controller
{
    public function index(Request $request, $audit_id = null)
    {
        $auditId = $request->audit_id;
        $periode = session('periode');
        $status = $request->status;
        $search = $request->search;
        if (! $auditId) {
            $temuanQuery = auth()->user()
                ->temuanAudits()
                ->with([
                    'audit.periode',
                    'audit.unit',
                    'audit.temuan.users',
                ])
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
                );
            $temuanCollection = $temuanQuery->get();
            if ($status) {
                $temuanCollection = $temuanCollection
                    ->where('status', $status);
            }
            $audits = $temuanCollection
                ->pluck('audit')
                ->unique('id')
                ->values();
            $totalAudit = $audits->count();
            $perPage = 6;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $audits
                ->slice(($currentPage - 1) * $perPage, $perPage)
                ->values();
            $audits = new LengthAwarePaginator(
                $currentItems,
                $totalAudit,
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );
            $totalTemuan = $temuanCollection->count();
            $totalOpen = $temuanCollection
                ->where('status', 'OPEN')
                ->count();
            $totalClosed = $temuanCollection
                ->where('status', 'CLOSED')
                ->count();
            return view(
                'temuan.audit-index',
                compact(
                    'audits',
                    'totalAudit',
                    'totalTemuan',
                    'totalOpen',
                    'totalClosed'
                )
            );
        }

        $query = auth()->user()
            ->temuanAudits()
            ->with(
                'audit',
                'audit.unit',
                'audit.periode',
                'audit.wakilAuditi',
                'audit.leadAuditor',
                'audit.auditor1',
                'audit.auditor2'
            )
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
        $query->where('audit_id', $auditId);
        $selectedTemuan = $request->temuan;
        $page = $request->page ?? 1;
        if ($selectedTemuan && ! $request->has('page')) {
            $allIds = (clone $query)
                ->orderBy('temuan_audits.id')
                ->pluck('temuan_audits.id')
                ->values();
            $position = $allIds->search((int) $selectedTemuan);
            if ($position !== false) {
                $targetPage = floor($position / 5) + 1;
                return redirect()->route('temuan.index', [
                    'audit_id' => $auditId,
                    'temuan' => $selectedTemuan,
                    'page' => $targetPage,
                ]);
            }
        }
        $totalTemuan = $query->count();

        $totalOpen = (clone $query)
            ->where('status', 'OPEN')
            ->count();

        $totalClosed = (clone $query)
            ->where('status', 'CLOSED')
            ->count();

        $temuan = $query
            ->paginate(5)
            ->withQueryString();

        $audit = $temuan->first()?->audit;

        return view('temuan.index', compact('temuan', 'selectedTemuan', 'audit', 'totalTemuan', 'totalOpen', 'totalClosed'));
    }

    public function update(Request $request, $id)
    {
        $temuan = TemuanAudit::findOrFail($id);
        // hanya bisa edit jika OPEN
        if ($temuan->status === 'CLOSED') {
            return back()->with('error', 'Temuan sudah ditutup');
        }
        $temuan->update([
            'tindakan_perbaikan_awal' => $request->tindakan,
            'bukti_link' => $request->bukti,
            'needs_review' => true,
            'review_finalized' => false,
        ]);
        $asesor = User::whereHas(
            'role',
            function ($q) {
                $q->where('nama', 'ASESOR');
            }
        )->get();

        foreach ($asesor as $user) {
            $user->notify(
                new TemuanNotification(
                    'Tindak Lanjut Baru',
                    'Indikator '.$temuan->kode_indikator.' telah ditindaklanjuti.',
                    route('asesor.audit.show', [
                        $temuan->audit_id,
                        'mode' => 'review',
                        'temuan' => $temuan->id,
                    ])
                )
            );
        }
        return back()->with('success', 'Berhasil disimpan');
    }
}
