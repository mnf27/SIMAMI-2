<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\InstrumentTemplate;
use App\Models\Periode;
use App\Models\TemuanAudit;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\TemuanImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Notifications\TemuanNotification;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('temuan', 'unit');
        $units = Unit::orderBy('nama')->get();
        $periode = session('periode');
        $query->when(
            $periode,
            function ($q) use ($periode) {
                $q->whereHas(
                    'periode',
                    function ($sub) use ($periode) {
                        $sub->where('kode', $periode);
                    }
                );
            }
        );
        if ($request->unit_id) {
            $query->where(
                'unit_id',
                $request->unit_id
            );
        }
        if ($request->status) {
            $query->whereHas('temuan', function ($q) use ($request) {
                $q->where(
                    'status',
                    $request->status
                );
            });
        }
        $statQuery = clone $query;
        // Statistik
        $totalAudit = (clone $query)->count();
        $totalTemuan = TemuanAudit::whereHas('audit', function ($q) use ($request, $periode) {
            if ($periode) {
                $q->whereHas(
                    'periode',
                    function ($sub) use ($periode) {
                        $sub->where('kode', $periode);
                    }
                );
            }
            if ($request->filled('unit_id')) {
                $q->where('unit_id', $request->unit_id);
            }
        })->count();
        $totalOpen = TemuanAudit::where('status', 'OPEN')
            ->whereHas('audit', function ($q) use ($request, $periode) {
                if ($periode) {
                    $q->whereHas(
                        'periode',
                        function ($sub) use ($periode) {
                            $sub->where('kode', $periode);
                        }
                    );
                }
                if ($request->filled('unit_id')) {
                    $q->where('unit_id', $request->unit_id);
                }
            })
            ->count();
        $totalClosed = TemuanAudit::where('status', 'CLOSED')
            ->whereHas('audit', function ($q) use ($request, $periode) {
                if ($periode) {
                    $q->whereHas(
                        'periode',
                        function ($sub) use ($periode) {
                            $sub->where('kode', $periode);
                        }
                    );
                }
                if ($request->filled('unit_id')) {
                    $q->where('unit_id', $request->unit_id);
                }
            })
            ->count();
        $periodes = Periode::orderBy('kode', 'desc')->get();
        $users = User::orderBy('name')->get();
        $asesor = User::whereHas(
            'role',
            fn ($q) => $q->where('nama', 'ASESOR')
        )->orderBy('name')->get();
        $audits = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();
        return view(
            'asesor.audit.index',
            compact(
                'audits',
                'units',
                'periode',
                'totalAudit',
                'totalTemuan',
                'totalOpen',
                'totalClosed',
                'periodes',
                'users',
                'asesor'
            )
        );
    }

    public function create()
    {
        $periodes = Periode::orderBy('kode', 'desc')->get();
        $units = Unit::orderBy('nama')->get();
        $users = User::all();
        $asesor = User::whereHas('role', function ($q) {
            $q->where('nama', 'ASESOR');
        })->get();
        return view('asesor.audit.create', compact('periodes', 'units', 'users', 'asesor'));
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
        $template = InstrumentTemplate::where('is_active', true)->first();
        $audit = Audit::create([
            'nama_audit' => $request->nama_audit,
            'periode_id' => $request->periode_id,
            'instrument_template_id' => $template?->id,
            'tanggal_audit' => now(),
            'unit_id' => $request->unit_id,
            'wakil_auditi_id' => $request->wakil_auditi_id,
            'auditor_1_id' => $request->auditor_1_id,
            'auditor_2_id' => $request->auditor_2_id,
            'dibuat_oleh' => auth()->id(),
            'lead_auditor_id' => $request->lead_auditor_id,
        ]);
        return redirect()->route('asesor.audit.index')
            ->with('success', 'Audit berhasil dibuat');
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

    public function downloadTemplate($id)
    {
        $audit = Audit::with('instrumentTemplate')->findOrFail($id);
        $template = $audit->instrumentTemplate ?? InstrumentTemplate::where('is_active', true)->first();

        if (! $template) {
            return back()->with('error', 'Template belum tersedia.');
        }

        return Storage::disk('public')->download($template->path, $template->nama_file);
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
            Excel::import(
                new TemuanImport(
                    $audit->id,
                    $audit->unit_id
                ),
                $request->file('file')
            );
            return back()->with(
                'success',
                'Instrumen audit berhasil diimport.'
            );
        } catch (\Throwable $e) {
            \Log::error('Import Instrumen Gagal', [
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
                        'temuan' => $temuan->id
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

    public function export($id)
    {
        $audit = Audit::with([
            'temuan',
            'unit',
            'wakilAuditi',
            'auditor1',
            'auditor2',
            'leadAuditor',
            'instrumentTemplate',
        ])->findOrFail($id);
        $template = $audit->instrumentTemplate;

        if (! $template) {
            return back()->with('error', 'Template instrumen untuk audit ini tidak ditemukan.');
        }

        $templatePath = storage_path('app/public/'.$template->path);
        if (! file_exists($templatePath)) {
            return back()->with('error', 'File template tidak ditemukan di penyimpanan.');
        }
        $spreadsheet = IOFactory::load($templatePath);
        $sheetInstrumen = $spreadsheet->getSheetByName('Instrumen Audit SPMI 2021');
        $sheetHasil = $spreadsheet->getSheetByName('Hasil AMI  Tindak lanjut');
        $sheet = $spreadsheet->getSheetByName('PTPP');
        // Instrumen Audit SPMI
        $sheetInstrumen->setCellValue(
            'B1',
            strtoupper(
                $audit->unit->nama ?? '-'
            )
        );
        $sheetInstrumen->setCellValue(
            'B2',
            strtoupper(
                $audit->unit->lokasi ?? '-'
            )
        );
        $periode = $audit->periode->kode;
        [$tahun, $semester] = explode('-', $periode);
        $tahun = (int) $tahun;
        $semester = (int) $semester;
        if ($semester == '1') {
            $semesterText = 'Genap';
            $tahunAwal = $tahun - 1;
            $tahunAkhir = $tahun;
        } else {
            $semesterText = 'Ganjil';
            $tahunAwal = $tahun;
            $tahunAkhir = $tahun + 1;
        }
        $dueDate = $periode.
            ' ('.
            $semesterText.' '.
            $tahunAwal.'/'.$tahunAkhir.
            ')';
        $sheetInstrumen->setCellValue(
            'B3',
            $dueDate
        );
        $sheetInstrumen->setCellValue(
            'C2',
            $audit->wakilAuditi->name ?? '-'
        );
        $sheetInstrumen->setCellValue(
            'C3',
            Carbon::parse(
                $audit->tanggal_audit
            )
                ->locale('en')
                ->translatedFormat('l, d F Y')
        );
        $sheetInstrumen->setCellValue(
            'F5',
            $audit->auditor1->name ?? '-'
        );
        $sheetInstrumen->setCellValue(
            'G5',
            $audit->auditor2->name ?? '-'
        );
        $dataStartRowInstrumen = 7;
        $temuanInstrumen = $audit->temuan->values();
        $totalInstrumen = $temuanInstrumen->count();
        if ($totalInstrumen > 1) {
            $sheetInstrumen->insertNewRowBefore(
                $dataStartRowInstrumen + 1,
                $totalInstrumen - 1
            );
            for ($i = 1; $i < $totalInstrumen; $i++) {
                $sourceRow = $dataStartRowInstrumen;
                $targetRow = $dataStartRowInstrumen + $i;
                $sheetInstrumen->duplicateStyle(
                    $sheetInstrumen->getStyle(
                        "A$sourceRow:H$sourceRow"
                    ),
                    "A$targetRow:H$targetRow"
                );
                $sheetInstrumen->getRowDimension($targetRow)
                    ->setRowHeight(
                        $sheetInstrumen
                            ->getRowDimension($sourceRow)
                            ->getRowHeight()
                    );
                $sheetInstrumen->getStyle(
                    "D$targetRow:H$targetRow"
                )->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFFFFFF');
            }
        }
        $rowInstrumen = $dataStartRowInstrumen;
        $rowInstrumen = 7;
        foreach ($temuanInstrumen as $t) {
            $sheetInstrumen->setCellValue(
                "A$rowInstrumen",
                $t->kode_indikator
            );
            $sheetInstrumen->setCellValue(
                "B$rowInstrumen",
                $t->temuan
            );
            $sheetInstrumen->setCellValue(
                "C$rowInstrumen",
                $t->hasil_ami
            );
            $sheetInstrumen->setCellValue(
                "D$rowInstrumen",
                $t->tindakan_perbaikan_awal
            );
            $link = trim($t->bukti_link ?? '');
            $sheetInstrumen->setCellValue(
                "E$rowInstrumen",
                $link
            );
            if (! empty($link)) {
                $sheetInstrumen->getCell(
                    "E$rowInstrumen"
                )->getHyperlink()
                    ->setUrl($link);
                $sheetInstrumen->getStyle(
                    "E$rowInstrumen"
                )->applyFromArray([
                            'font' => [
                                'underline' => true,
                            ],
                        ]);
            }
            $sheetInstrumen->setCellValue(
                "F$rowInstrumen",
                $t->tanggapan_auditor
            );
            $sheetInstrumen->setCellValue(
                "G$rowInstrumen",
                $t->tanggapan_auditor_2
            );
            $sheetInstrumen->setCellValue(
                "H$rowInstrumen",
                $t->status
            );
            $sheetInstrumen->getStyle(
                "H$rowInstrumen"
            )->getFont()->setSize(16);
            $sheetInstrumen->getStyle(
                "A$rowInstrumen:H$rowInstrumen"
            )->getAlignment()
                ->setWrapText(true);
            $sheetInstrumen->getStyle(
                "A$rowInstrumen"
            )->getAlignment()
                ->setVertical(
                    Alignment::VERTICAL_CENTER)
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER);
            $sheetInstrumen->getStyle(
                "B$rowInstrumen:H$rowInstrumen"
            )->getAlignment()
                ->setVertical(
                    Alignment::VERTICAL_CENTER)
                ->setHorizontal(
                    Alignment::HORIZONTAL_LEFT);
            $rowInstrumen++;
        }
        // Hasil AMI Tindak Lanjut
        // Header
        $sheetHasil->setCellValue(
            'C10',
            strtoupper(
                ($audit->unit->nama ?? '').' '.
                ($audit->unit->lokasi ?? '')
            )
        );
        $sheetHasil->setCellValue(
            'F10',
            Carbon::parse(
                $audit->tanggal_audit
            )->translatedFormat('l, d F Y')
        );
        // Tabel
        $dataStartRowHasil = 13;
        $temuanHasil = $audit->temuan
            ->whereIn('status', ['OPEN', 'CLOSED'])
            ->values();
        $totalHasil = $temuanHasil->count();
        if ($totalHasil > 1) {
            $sheetHasil->insertNewRowBefore(
                $dataStartRowHasil + 1,
                $totalHasil - 1
            );
            for ($i = 0; $i < $totalHasil - 1; $i++) {
                $sheetHasil->duplicateStyle(
                    $sheetHasil->getStyle('A13:G13'),
                    'A'.(13 + $i).':G'.(13 + $i)
                );
            }
        }
        // isi
        $rowHasil = $dataStartRowHasil;
        foreach ($temuanHasil as $t) {
            $sheetHasil->setCellValue(
                "A$rowHasil",
                $t->kode_indikator
            );
            $sheetHasil->setCellValue(
                "B$rowHasil",
                $t->temuan
            );
            $sheetHasil->setCellValue(
                "C$rowHasil",
                $t->hasil_ami
            );
            $sheetHasil->setCellValue(
                "D$rowHasil",
                $t->tanggapan_auditor
            );
            $sheetHasil->setCellValue(
                "E$rowHasil",
                $t->status == 'OPEN'
                ? 'Monev Prodi'
                : '-'
            );
            $periode = $audit->periode->kode;
            [$tahun, $semester] = explode('-', $periode);
            $tahun = (int) $tahun;
            $semester = (int) $semester;
            if ($semester == '1') {
                $semesterText = 'Genap';
                $tahunAwal = $tahun - 1;
                $tahunAkhir = $tahun;
            } else {
                $semesterText = 'Ganjil';
                $tahunAwal = $tahun;
                $tahunAkhir = $tahun + 1;
            }
            $sheetInstrumen->setCellValue(
                'B3',
                $dueDate
            );
            $sheetHasil->setCellValue(
                "F$rowHasil",
                $dueDate
            );
            if ($t->status == 'OPEN') {
                $sheetHasil->getStyle("G$rowHasil")
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB('FF0000');
                $sheetHasil->getStyle("G$rowHasil")
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');
            } else {
                $sheetHasil->getStyle("G$rowHasil")
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB('00FF00');
                $sheetHasil->getStyle("G$rowHasil")
                    ->getFont()
                    ->getColor()
                    ->setARGB('000000');
            }
            $sheetHasil->setCellValue(
                "G$rowHasil",
                $t->status
            );
            // style
            $sheetHasil->getStyle(
                "A$rowHasil:G$rowHasil"
            )->getAlignment()
                ->setWrapText(true);
            $sheetHasil->getStyle(
                "A$rowHasil"
            )->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetHasil->getStyle(
                "B$rowHasil:D$rowHasil"
            )->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheetHasil->getStyle(
                "E$rowHasil:G$rowHasil"
            )->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetHasil->getRowDimension($rowHasil)
                ->setRowHeight(70);
            $rowHasil++;
        }
        $footerHasil = $rowHasil + 1;
        // Auditi
        $sheetHasil->setCellValue(
            "E".($footerHasil + 2),
            $audit->wakilAuditi->name ?? '-'
        );
        $sheetHasil->setCellValue(
            "E".($footerHasil + 3),
            Carbon::parse(
                $audit->tanggal_audit
            )->translatedFormat('l, d F Y')
        );
        // Auditor
        $sheetHasil->setCellValue(
            "F".($footerHasil + 2),
            optional($audit->auditor1)->name ?? '-'
        );
        $sheetHasil->setCellValue(
            "F".($footerHasil + 3),
            Carbon::parse(
                $audit->tanggal_audit
            )->translatedFormat('l, d F Y')
        );
        // PTPP
        $sheet->getStyle('A7')
            ->getFont()
            ->getColor()
            ->setARGB('0000FF');
        $sheet->getStyle('A7')
            ->getFont()
            ->setUnderline(true);
        $sheet->setCellValue(
            'A11',
            strtoupper(
                ($audit->unit->nama ?? '-').' '.($audit->unit->lokasi ?? '-')
            )
        );
        $sheet->getStyle('A18:M18')
            ->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_CENTER
            )
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );
        $sheet->getStyle('A18:M18')
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getStyle('A18:M18')
            ->getFont()
            ->setSize(10);
        $sheet->setCellValue(
            'A13',
            'KPS / KEPALA UNIT'
        );
        $sheet->setCellValue(
            'A15',
            $audit->wakilAuditi->name ?? '-'
        );
        $sheet->setCellValue(
            'D13',
            'Temuan hasil audit internal'
        );
        $sheet->setCellValue(
            'I13',
            Carbon::parse(
                $audit->tanggal_audit
            )->format('d/m/Y')
        );
        $sheet->setCellValue(
            'D15',
            optional($audit->auditor1)->name ?? '-'
        );
        $sheet->setCellValue(
            'I15',
            optional($audit->auditor2)->name ?? '-'
        );
        $dataStartRow = 19;
        $temuan = $audit->temuan->values();
        $totalData = $temuan->count();
        if ($totalData > 1) {
            $sheet->insertNewRowBefore(
                $dataStartRow + 1,
                $totalData - 1
            );
            for ($i = 0; $i < $totalData - 1; $i++) {
                $sheet->duplicateStyle(
                    $sheet->getStyle('A19:M19'),
                    'A'.(20 + $i).':M'.(20 + $i)
                );
            }
        }
        $row = $dataStartRow;
        foreach ($temuan as $t) {
            $sheet->setCellValue(
                "A$row",
                $t->kode_indikator
            );
            $sheet->setCellValue(
                "B$row",
                $t->temuan
            );
            $sheet->setCellValue(
                "C$row",
                $t->hasil_ami
            );
            $sheet->setCellValue(
                "D$row",
                $t->tanggapan_auditor
            );
            $sheet->setCellValue(
                "E$row",
                'TIDAK SESUAI SPMI'
            );
            $sheet->setCellValue(
                "F$row",
                'Tidak Tercapai'
            );
            $sheet->setCellValue(
                "G$row",
                'SETUJU'
            );
            $sheet->setCellValue(
                "H$row",
                'Perbaikan sesuai standar SPMI dan hasil tinjauan manajemen'
            );
            $sheet->setCellValue(
                "I$row",
                'PERIODE AMI BERIKUTNYA'
            );
            $sheet->setCellValue(
                "J$row",
                'KPS'
            );
            $sheet->setCellValue(
                "K$row",
                'Monev Prodi'
            );
            $sheet->setCellValue(
                "L$row",
                '-'
            );
            $sheet->setCellValue(
                "M$row",
                $t->status
            );
            if ($t->status === 'CLOSED') {
                $sheet->getStyle("M$row")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FF00FF00');
            }
            $sheet->getStyle("A$row:M$row")
                ->getAlignment()
                ->setWrapText(true);
            $sheet->getStyle("A$row")
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B$row:D$row")
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("E$row:M$row")
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)
                ->setRowHeight(70);
            $row++;
        }
        $footerRow = $row + 1;
        $sheet->setCellValue(
            "C".($footerRow + 1),
            $audit->wakilAuditi->name ?? '-'
        );
        $sheet->setCellValue(
            "I".($footerRow + 1),
            optional($audit->auditor1)->name ?? '-'
        );
        $sheet->setCellValue(
            "C".($footerRow + 4),
            optional($audit->leadAuditor)->name ?? '-'
        );
        $sheet->getStyle(
            "C".($footerRow + 1).":D".($footerRow + 1)
        )->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(
            "E".($footerRow + 1)
        )->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle(
            "L".($footerRow + 1)
        )->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle(
            "G".($footerRow + 4)
        )->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $writer = IOFactory::createWriter(
            $spreadsheet,
            'Xlsx'
        );
        $filename = 'Audit-'.$audit->id.'.xlsx';
        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public function destroy($id)
    {
        $audit = Audit::findOrFail($id);
        $audit->delete();
        return back()->with('success', 'Audit dihapus');
    }
}
