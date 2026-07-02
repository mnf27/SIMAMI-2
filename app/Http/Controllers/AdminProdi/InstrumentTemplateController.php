<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\InstrumentTemplate;
use Illuminate\Http\Request;

class InstrumentTemplateController extends Controller
{
    public function index()
    {
        $templates = InstrumentTemplate::with('uploader')->withCount('audits')->latest()->paginate(10);
        return view('admin.template.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls|max:5120',
            'versi' => 'nullable|string|max:100']);
        $file = $request->file('file');
        $path = $file->store('template', 'public');

        InstrumentTemplate::create(['nama_file' => $file->getClientOriginalName(), 'path' => $path,
            'versi' => $request->versi, 'is_active' => InstrumentTemplate::count() === 0,
            'uploaded_by' => auth()->id(),]);

        return back()->with('success', 'Template instrumen berhasil diupload.');
    }

    public function activate(InstrumentTemplate $template)
    {
        InstrumentTemplate::query()->update(['is_active' => false,]);

        $template->update(['is_active' => true,]);

        return back()->with('success', 'Template berhasil diaktifkan.');
    }
}
