@props([
    'open',
    'title' => '',
    'description' => '',
    'maxWidth' => 'max-w-lg',
])
{{-- Overlay --}}
<div
    x-cloak
    x-show="{{ $open }}"
    x-transition.opacity
    class="fixed inset-0 z-[997] bg-black/40"
    @click="{{ $open }} = false">
</div>
{{-- Modal --}}
<div
    x-show="{{ $open }}"
    x-transition
    @click.self="{{ $open }} = false"
    @keydown.escape.window="{{ $open }} = false"
    class="fixed inset-0 z-[999] flex items-center justify-center p-3">
    <div
        @click.stop
        class="w-full {{ $maxWidth }} rounded-2xl bg-white shadow-2xl">
        {{-- Header --}}
        <div class="pt-3 pb-2 pl-4 pr-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $title }}
                    </h3>
                </div>
                <button
                    type="button"
                    @click="{{ $open }} = false"
                    class="rounded-lg p-1 hover:bg-slate-200 transition">
                    <i
                        data-lucide="x"
                        class="w-5 h-5">
                    </i>
                </button>
            </div>
        </div>
        {{-- Content --}}
        {{ $slot }}
    </div>
</div>