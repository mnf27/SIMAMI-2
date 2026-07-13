<?php

namespace App\Http\Controllers\Auditi;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\TemuanAudit;
use Illuminate\Http\Request;
use App\Notifications\TemuanNotification;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class TemuanController extends Controller
{
    private function auditBelumDidistribusikan($auditId): bool
    {
        return TemuanAudit::where('audit_id', $auditId)
            ->doesntHave('users')
            ->exists();
    }

    public function index(Request $request, $audit_id = null)
    {
        $auditId = $request->audit_id;
        $periode = session('periode');
        $status = $request->status;
        $search = $request->search;
        if (! $auditId) {
            if (auth()->user()->role->nama === 'KPS') {

                // KPS melihat semua audit
                $auditQuery = Audit::with([
                    'periode',
                    'unit',
                    'temuan.users',
                ]);

                if ($periode) {
                    $auditQuery->whereHas('periode', function ($q) use ($periode) {
                        $q->where('kode', $periode);
                    });
                }

                $audits = $auditQuery->get();

                if ($status) {
                    $audits = $audits->filter(function ($audit) use ($status) {
                        return $audit->temuan->where('status', $status)->count() > 0;
                    })->values();
                }

                $totalAudit = $audits->count();

                $totalTemuan = $audits->sum(function ($audit) {
                    return $audit->temuan->count();
                });

                $totalOpen = $audits->sum(function ($audit) {
                    return $audit->temuan->where('status', 'OPEN')->count();
                });

                $totalClosed = $audits->sum(function ($audit) {
                    return $audit->temuan->where('status', 'CLOSED')->count();
                });

            } else {

                // Role lain hanya melihat temuan yang menjadi tanggung jawabnya
                $temuanQuery = auth()->user()
                    ->temuanAudits()
                    ->with([
                        'audit.periode',
                        'audit.unit',
                        'audit.temuan.users',
                    ]);

                if ($periode) {
                    $temuanQuery->whereHas('audit.periode', function ($q) use ($periode) {
                        $q->where('kode', $periode);
                    });
                }

                $temuanCollection = $temuanQuery->get();

                if ($status) {
                    $temuanCollection = $temuanCollection->where('status', $status);
                }

                $audits = $temuanCollection
                    ->pluck('audit')
                    ->unique('id')
                    ->values();

                $totalAudit = $audits->count();

                $totalTemuan = $temuanCollection->count();

                $totalOpen = $temuanCollection->where('status', 'OPEN')->count();

                $totalClosed = $temuanCollection->where('status', 'CLOSED')->count();
            }

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

        if (auth()->user()->role->nama === 'KPS') {

            $query = TemuanAudit::with(
                'audit',
                'audit.unit',
                'audit.periode',
                'audit.wakilAuditi',
                'audit.leadAuditor',
                'audit.auditor1',
                'audit.auditor2'
            );

        } else {

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
                );

        }

        $query->when(
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

        // if (auth()->user()->role->nama === 'KPS' && $this->auditBelumDidistribusikan($auditId)) {
        //     return redirect()->route('temuan.distribusi', ['audit' => $auditId]);
        // }

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

    public function distribusi($auditId)
    {
        abort_if(auth()->user()->role->nama !== 'KPS', 403);

        $audit = Audit::with([
            'unit',
            'periode',
            'wakilAuditi',
            'leadAuditor',
            'auditor1',
            'auditor2',
        ])->findOrFail($auditId);

        $temuan = TemuanAudit::with('users')
            ->where('audit_id', $auditId)
            ->orderBy('id')
            ->paginate(5)
            ->withQueryString();

        $users = User::whereHas('role', function ($q) {
            $q->whereIn('nama', [
                'KPS',
                'ADMIN_PRODI',
                'DOSEN',
                'TEKNISI',
            ]);
        })
            ->with('role')
            ->orderBy('name')
            ->get();

        $totalTemuan = $audit->temuan()->count();

        $belumDistribusi = $audit->temuan()
            ->doesntHave('users')
            ->count();

        $sudahDistribusi = $totalTemuan - $belumDistribusi;

        return view(
            'temuan.distribusi',
            compact(
                'audit',
                'temuan',
                'users',
                'totalTemuan',
                'belumDistribusi',
                'sudahDistribusi'
            )
        );
    }

    public function update(Request $request, TemuanAudit $temuan)
    {
        if ($temuan->status === 'CLOSED') {
            return back()->with('error', 'Temuan sudah ditutup');
        }
        $temuan->update([
            'tindakan_perbaikan_awal' => $request->tindakan,
            'bukti_link' => $request->bukti,
            'needs_review' => true,
            'review_finalized' => false,
        ]);
        $asesor = User::whereHas('role', function ($q) {
            $q->where('nama', 'ASESOR');
        })->get();

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

    public function assign(Request $request, TemuanAudit $temuan)
    {
        abort_if(auth()->user()->role->nama !== 'KPS', 403);

        $request->validate([
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ]);

        $temuan->users()->sync($request->users);

        return back()->with(
            'success',
            'Distribusi berhasil disimpan.'
        );
    }
}
