<?php

namespace App\Http\Controllers\AdminProdi;

use App\Exports\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::with(['role', 'unit'])->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        })->latest()->paginate(6)->withQueryString();
        $totalUser = User::count();
        $totalAuditor = User::whereHas('role', function ($q) {
            $q->where('nama', 'ASESOR');
        })->count();
        $totalAuditi = User::whereHas('role', function ($q) {
            $q->where('nama', ['ADMIN_PRODI', 'KPS', 'DOSEN', 'TEKNISI']);
        })->count();
        $totalProdi = User::whereHas('role', function ($q) {
            $q->where('nama', ['ADMIN_PRODI', 'KPS', 'DOSEN']);
        })->count();
        $totalLab = User::whereHas('role', function ($q) {
            $q->where('nama', 'TEKNISI');
        })->count();
        return view('kps.user.index', compact(
            'users',
            'totalUser',
            'totalAuditor',
            'totalAuditi',
            'totalProdi',
            'totalLab'
        ));
    }

    public function exportTemplate()
    {
        return Excel::download(new UserTemplateExport(), 'template-users-simami.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ]);

        try {
            Excel::import(new UserImport(), $request->file('file'));

            return back()->with('success', 'User berhasil diimport.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
