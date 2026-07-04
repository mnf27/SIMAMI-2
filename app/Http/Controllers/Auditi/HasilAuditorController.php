<?php

namespace App\Http\Controllers\Auditi;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HasilAuditorController extends Controller
{
    public function index()
    {
        $audits = Audit::with(['periode', 'unit', 'leadAuditor',])
            ->where('unit_id', auth()->user()->unit_id)->whereNotNull('final_ptpp_pdf')->latest()
            ->paginate(10);

        return view('auditi.hasil-auditor.index', compact('audits'));
    }

    public function download(Audit $audit)
    {
        abort_if($audit->unit_id != auth()->user()->unit_id, 403,
            'Anda tidak memiliki akses terhadap dokumen ini.');

        if (! $audit->final_ptpp_pdf || ! Storage::disk('public')->exists($audit->final_ptpp_pdf)) {
            return back()->with('error', 'Dokumen PDF tidak ditemukan.');
        }

        $namaFile = 'PTPP Final_'.($audit->nama_audit).'_'.$audit->periode->kode.'.pdf';

        return Storage::disk('public')->download($audit->final_ptpp_pdf, $namaFile);
    }
}
