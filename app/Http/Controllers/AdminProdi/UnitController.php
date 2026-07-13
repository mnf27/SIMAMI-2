<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $units = Unit::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")->orWhere('jenis', 'like', "%$search%")->orWhere('lokasi', 'like', "%$search%");
            });
        })->latest()->paginate(10)->withQueryString();
        $totalUnit = Unit::count();
        $totalProdi = Unit::where('jenis', 'PRODI')->count();
        $totalLab = Unit::where('jenis', 'LAB')->count();
        return view('kps.unit.index', compact('units', 'totalUnit', 'totalProdi', 'totalLab'));
    }

    public function create()
    {
        return view('kps.unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required|in:PRODI,LAB',
            'lokasi' => 'nullable',
        ]);
        Unit::create($request->only(['nama', 'jenis', 'lokasi']));
        return redirect()->route('kps.units.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        return view('kps.unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required|in:PRODI,LAB',
            'lokasi' => 'nullable',
        ]);
        $unit->update($request->only(['nama', 'jenis', 'lokasi']));
        return redirect()->route('kps.units.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit berhasil dihapus.');
    }
}
