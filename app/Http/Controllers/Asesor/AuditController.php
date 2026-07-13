<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Periode;
use App\Models\TemuanAudit;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\TemuanImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\AuditExportService;
use App\Notifications\TemuanNotification;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('temuan', 'unit');
        $units = Unit::orderBy('nama')->get();
        $periode = session('periode');
        $query->when($periode, function ($q) use ($periode) {
            $q->whereHas('periode', function ($sub) use ($periode) {
                $sub->where('kode', $periode);
            });
        });

        if ($request->unit_id) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->status) {
            $query->whereHas('temuan', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $statQuery = clone $query;
        $totalAudit = (clone $query)->count();
        $totalTemuan = TemuanAudit::whereHas('audit', function ($q) use ($request, $periode) {
            if ($periode) {
                $q->whereHas('periode', function ($sub) use ($periode) {
                    $sub->where('kode', $periode);
                });
            }

            if ($request->filled('unit_id')) {
                $q->where('unit_id', $request->unit_id);
            }
        })->count();
        $totalOpen = TemuanAudit::where('status', 'OPEN')->whereHas('audit',
            function ($q) use ($request, $periode) {
                if ($periode) {
                    $q->whereHas('periode', function ($sub) use ($periode) {
                        $sub->where('kode', $periode);
                    });
                }

                if ($request->filled('unit_id')) {
                    $q->where('unit_id', $request->unit_id);
                }
            })->count();
        $totalClosed = TemuanAudit::where('status', 'CLOSED')->whereHas('audit',
            function ($q) use ($request, $periode) {
                if ($periode) {
                    $q->whereHas('periode', function ($sub) use ($periode) {
                        $sub->where('kode', $periode);
                    });
                }

                if ($request->filled('unit_id')) {
                    $q->where('unit_id', $request->unit_id);
                }
            })->count();
        $periodes = Periode::orderBy('kode', 'desc')->get();
        $users = User::orderBy('name')->get();
        $asesor = User::whereHas('role', fn ($q) => $q->where('nama', 'ASESOR'))->orderBy('name')->get();
        $audits = $query->latest()->paginate(6)->withQueryString();
        return view('asesor.audit.index', compact('audits', 'units', 'periode', 'totalAudit', 'totalTemuan',
            'totalOpen', 'totalClosed', 'periodes', 'users', 'asesor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_audit' => 'required|string|max:255',
            'periode_id' => 'required|exists:periodes,id',
            'unit_id' => 'required',
            'wakil_auditi_id' => 'required',
            'auditor_1_id' => 'required',
            'auditor_2_id' => 'nullable',
            'lead_auditor_id' => 'nullable',
        ]);
        Audit::create([
            'nama_audit' => $request->nama_audit,
            'periode_id' => $request->periode_id,
            'tanggal_audit' => now(),
            'unit_id' => $request->unit_id,
            'wakil_auditi_id' => $request->wakil_auditi_id,
            'auditor_1_id' => $request->auditor_1_id,
            'auditor_2_id' => $request->auditor_2_id,
            'dibuat_oleh' => auth()->id(),
            'lead_auditor_id' => $request->lead_auditor_id,
        ]);
        return redirect()->route('asesor.audit.index')->with('success', 'Audit berhasil dibuat');
    }

    public function show($id)
    {
        $audit = Audit::with([
            'periode',
            'temuan',
            'unit',
            'wakilAuditi',
            'auditor1',
            'auditor2',
        ])->findOrFail($id);
        $temuan = TemuanAudit::where('audit_id', $id)
            ->paginate(5);
        $reviewReady = $audit->temuan()
            ->where('status', 'OPEN')
            ->where('needs_review', true)
            ->count();
        $totalOpen = $audit->temuan()
            ->where('status', 'OPEN')
            ->count();
        $allReviewed = $totalOpen == 0;
        $reviewTemuan = $audit->temuan()
            ->where('status', 'OPEN')
            ->where('needs_review', true)
            ->get();
        return view('asesor.audit.show', compact('audit', 'temuan', 'reviewReady', 'reviewTemuan', 'allReviewed'));
    }

    public function temuan(Request $request)
    {
        $periode = session('periode');
        $unitId = $request->unit_id;
        $status = $request->status;
        $search = $request->search;
        $query = TemuanAudit::with(
            'audit',
            'audit.unit'
        );
        if ($periode) {
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
        if ($unitId) {
            $query->whereHas(
                'audit',
                function ($q) use ($unitId) {
                    $q->where(
                        'unit_id',
                        $unitId
                    );
                }
            );
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_indikator', 'like', "%{$search}%")
                    ->orWhere('temuan', 'like', "%{$search}%");
            });
        }
        $temuan = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();
        $units = Unit::orderBy('nama')->get();
        return view(
            'asesor.temuan.index',
            compact(
                'temuan',
                'units'
            )
        );
    }

    public function import(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        try {

            $audit = Audit::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Simpan file instrumen auditor pusat
            |--------------------------------------------------------------------------
            */
            DB::transaction(function () use ($request, $audit) {

                $path = $request->file('file')->store('instrumen', 'public');
                $sourcePath = storage_path('app/public/'.$path);

                Excel::import(
                    new TemuanImport($audit->id),
                    $sourcePath
                );

                $templatePath = storage_path(
                    'app/public/templates/audit_'.$audit->id.'.xlsx'
                );

                $templateGenerator = app(
                    \App\Services\TemplateExportGenerator::class
                );

                $templateGenerator->generate(
                    $sourcePath,
                    $templatePath
                );

                $audit->update([
                    'instrumen_path' => 'templates/audit_'.$audit->id.'.xlsx',
                ]);

            });

            return back()->with(
                'success',
                'Temuan audit berhasil diimpor.'
            );

        } catch (\Throwable $e) {

            \Log::error('Impor Temuan Audit Gagal', [
                'audit_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function review(Request $request, $id)
    {
        $temuan = TemuanAudit::with('audit')->findOrFail($id);
        $audit = $temuan->audit;
        $userId = auth()->id();
        if ($audit->isAuditor1($userId)) {
            $temuan->update([
                'hasil_ami' => $request->hasil_ami,
                'tanggapan_auditor' => $request->tanggapan,
            ]);
            return redirect(
                route(
                    'asesor.audit.show',
                    [
                        'id' => $audit->id,
                        'mode' => 'review',
                        'temuan' => $temuan->id,
                    ]
                ).'#temuan-'.$temuan->id
            )->with(
                    'success',
                    'Review Auditor 1 disimpan'
                );
        }
        if ($audit->isAuditor2($userId)) {
            $temuan->update([
                'tanggapan_auditor_2' => $request->tanggapan,
            ]);
            return redirect(
                route(
                    'asesor.audit.show',
                    [
                        'id' => $audit->id,
                        'mode' => 'review',
                        'temuan' => $temuan->id,
                    ]
                ).'#temuan-'.$temuan->id
            )->with(
                    'success',
                    'Review Auditor 2 disimpan'
                );
        }
        abort(403, 'Anda bukan auditor audit ini');
    }

    public function validasi(Request $request, $id)
    {
        $temuan = TemuanAudit::findOrFail($id);
        $temuan->update([
            'status' => $request->status,
            'review_finalized' => true,
            'needs_review' => false,
        ]);
        $users = $temuan->users;
        foreach ($users as $user) {
            $user->notify(
                new TemuanNotification(
                    'Review Temuan Selesai',
                    'Indikator '.$temuan->kode_indikator.' telah direview.',
                    route('temuan.index', [
                        'audit_id' => $temuan->audit_id,
                        'temuan' => $temuan->id,
                    ])
                )
            );
        }
        return redirect(
            route(
                'asesor.audit.show',
                [
                    'id' => $temuan->audit_id,
                    'mode' => 'review',
                    'temuan' => $temuan->id,
                ]
            ).'#temuan-'.$temuan->id
        )->with(
                'success',
                'Status diperbarui'
            );
    }

    public function export($id, AuditExportService $exportService)
    {
        try {

            return $exportService->export($id);

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );

        }
    }

    public function uploadFinalPdf(Request $request, $id)
    {
        $request->validate(['file' => 'required|mimes:pdf|max:10240',]);
        $audit = Audit::findOrFail($id);

        if ($audit->final_ptpp_pdf && Storage::disk('public')->exists($audit->final_ptpp_pdf)) {
            Storage::disk('public')->delete($audit->final_ptpp_pdf);
        }

        $path = $request->file('file')->store('final-ptpp', 'public');
        $audit->update([
            'final_ptpp_pdf' => $path,
        ]);

        return back()->with(
            'success',
            'Dokumen PDF final berhasil diunggah.'
        );
    }

    public function destroy($id)
    {
        $audit = Audit::findOrFail($id);
        $audit->delete();
        return back()->with('success', 'Audit dihapus');
    }
}
