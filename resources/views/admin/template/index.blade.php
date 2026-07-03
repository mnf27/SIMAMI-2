<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div x-cloak x-data="{ openUpload: {{ $errors->has('file') ? 'true' : 'false' }}, openActivate: false,
    selectedTemplate: null }" class="py-[15px] lg:py-[14px] lg:py-[12px]">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="file-spreadsheet" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:items-end lg:flex-row lg:justify-between gap-2    ">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Template Instrumen
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm leading-relaxed">
                            Kelola template instrumen audit yang digunakan asesor.
                        </p>
                    </div>
                    <button @click="openUpload = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/10 backdrop-blur-sm pl-3 py-2 pr-6 text-white font-medium shadow-lg active:bg-white/30 lg:hover:bg-white/30 transition">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        Tambah Template
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm py-3 px-4 mb-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 mb-2">
                        <h3 class="text-base font-bold text-slate-800">
                            Daftar Template Intrumen
                        </h3>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($templates as $template)
                        <div
                            class="group rounded-xl p-3 transition-all duration-300 hover:shadow-md {{ $template->is_active ? 'border border-[#1E3A8A]/20 bg-[#E0E8FF]/20 hover:bg-white' : 'border border-slate-200 bg-white hover:border-slate-300' }}">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    {{-- Tombol Aktif --}}
                                    <form action="{{ route('admin.templates.activate', $template) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="shrink-0 group absolute">
                                            @if($template->is_active)
                                                <div
                                                    class="flex h-4 w-4 items-center justify-center rounded-full border-2 border-emerald-500">
                                                    <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                                </div>
                                            @else
                                                <div
                                                    class="h-4 w-4 rounded-full border-2 border-slate-300 group-hover:border-emerald-500">
                                                </div>
                                            @endif
                                        </button>
                                    </form>
                                    {{-- Icon --}}
                                    <div
                                        class="ml-3 flex h-10 w-10 items-center justify-center rounded-xl {{ $template->is_active ? 'bg-white text-[#1E3A8A]' : 'bg-[#A5BCFF]/20 text-[#1E3A8A]' }}">
                                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                                    </div>
                                    {{-- Informasi --}}
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-md font-bold text-slate-800">
                                                {{ $template->versi ?? '-' }}
                                            </p>
                                            @if($template->is_active)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-emerald-200 px-2 text-[10px] font-bold text-emerald-800">
                                                    AKTIF
                                                </span>
                                            @endif
                                        </div>
                                        <p class="max-w-md truncate text-sm text-slate-500"
                                            title="{{ $template->nama_file }}">
                                            {{ $template->nama_file }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $template->uploader->name }}
                                            •
                                            {{ $template->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                {{-- Informasi kanan --}}
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end pr-2">
                                    @if($template->audits_count)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-[#1E3A8A]/10 px-2.5 py-1 text-[11px] font-semibold text-[#1E3A8A]">
                                            <i data-lucide="clipboard-check" class="w-3 h-3"></i>
                                            {{ $template->audits_count }} Audit
                                        </span>
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-600">
                                            Digunakan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-medium text-amber-600">
                                            Belum Digunakan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                            <div class="flex flex-col items-center">
                                <div
                                    class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                    <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    Belum ada template instrumen.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Upload template instrumen pertama untuk digunakan pada proses audit.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-4">
                {{ $templates->links() }}
            </div>
        </div>
        <x-ui.modal open="openUpload" title="Upload Template Instrumen" maxWidth="max-w-md">
            <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-3 space-y-4">
                    {{-- Versi --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">
                            Versi Template
                        </label>
                        <input type="text" name="versi" placeholder="Contoh: AMI 2026"
                            class="w-full rounded-lg border-slate-300 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]">
                        @error('versi')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    {{-- Upload File --}}
                    <div x-data="{ fileName:'', isDragging:false, dragCounter:0 }">
                        <input x-ref="file" type="file" name="file" required class="hidden" accept=".xlsx,.xls"
                            @change="fileName = $event.target.files[0]?.name || ''">
                        <div @click="$refs.file.click()" @dragover.prevent="isDragging = true"
                            @dragenter.prevent="dragCounter++; isDragging = true"
                            @dragleave.prevent=" dragCounter--; if(dragCounter <= 0){ isDragging = false; dragCounter = 0;} "
                            @drop.prevent="dragCounter = 0; isDragging = false; $refs.file.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name || '';"
                            class="cursor-pointer rounded-xl border-2 border-dashed p-10 text-center transition"
                            :class="isDragging ? 'border-[#1E3A8A] bg-[#EEF2FF]' : 'border-slate-300 bg-slate-50 hover:border-[#1E3A8A] hover:bg-[#EEF2FF]'">
                            <div class="flex flex-col items-center">
                                <i data-lucide="file-spreadsheet" class="w-8 h-8 mb-2 text-slate-400"></i>
                                <p class="font-semibold text-slate-700"
                                    x-text="isDragging ? 'Lepaskan File di Sini' : 'Tarik File Template ke Sini'">
                                </p>
                                <p class="text-sm text-slate-500"
                                    x-text="isDragging ? 'File akan diunggah setelah dilepas' : 'atau klik untuk memilih file'">
                                </p>
                                <template x-if="fileName">
                                    <div
                                        class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#E0E8FF] px-3 py-1 text-xs font-medium text-[#1E3A8A]">
                                        <i data-lucide="file" class="w-3 h-3"></i>
                                        <span x-text="fileName"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('file')
                            <div class="mt-2 flex items-center gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
                                <span class="text-xs text-red-600">
                                    {{ $message }}
                                </span>
                            </div>
                        @enderror
                        <p class="mt-2 text-xs text-slate-400">
                            Format: .xlsx, .xls (maks. 5 MB)
                        </p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 px-3 pb-3">
                    <button type="button" @click="openUpload = false"
                        class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-300">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-[#1E3A8A] px-3 py-2 text-sm font-medium text-white hover:bg-[#152F79]">
                        Upload
                    </button>
                </div>
            </form>
        </x-ui.modal>
        <x-ui.modal open="openActivate" title="Aktifkan Template" maxWidth="max-w-md">
            <form :action="'/admin/templates/' + selectedTemplate + '/activate'" method="POST">
                @csrf
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">
                                Aktifkan template ini?
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Template yang sedang aktif akan
                                dinonaktifkan secara otomatis.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 px-4 pb-4">
                    <button type="button" @click="openActivate = false"
                        class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-300">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Aktifkan
                    </button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</x-app-layout>