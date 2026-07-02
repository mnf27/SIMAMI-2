<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $periodes = Periode::withCount('audits')->when($search, function ($query) use ($search) {
            $query->where('kode', 'like', "%{$search}%");
        })->latest()->orderByDesc('kode')->paginate(6)->withQueryString();
        $periodeAktif = Periode::withCount('audits')->where('is_active', true)->first();
        $totalPeriode = Periode::count();
        $periodeDigunakan = Periode::has('audits')->count();
        $totalAudit = Audit::count();
        return view('admin.periode.index', compact(
            'periodes',
            'periodeAktif',
            'totalPeriode',
            'periodeDigunakan',
            'totalAudit'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:9999',
            ],
            'semester' => [
                'required',
                'in:1,2',
            ],
        ]);
        $kode = $request->tahun.'-'.$request->semester;
        Periode::firstOrCreate([
            'kode' => $kode
        ]);
        return back()->with(
            'success',
            'Periode berhasil ditambahkan.'
        );
    }

    public function activate($id)
    {
        DB::transaction(function () use ($id) {
            // Nonaktifkan semua periode
            Periode::query()->update([
                'is_active' => false
            ]);
            // Aktifkan periode yang dipilih
            Periode::where('id', $id)
                ->update([
                    'is_active' => true
                ]);
        });
        return back()->with(
            'success',
            'Periode aktif berhasil diperbarui.'
        );
    }

    public function destroy(Periode $periode)
    {
        if ($periode->audits()->exists()) {
            return back()->with(
                'error',
                'Periode tidak dapat dihapus karena sudah digunakan audit.'
            );
        }
        $periode->delete();
        return back()->with(
            'success',
            'Periode berhasil dihapus.'
        );
    }
}
