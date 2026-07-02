@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="sm:hidden mt-3">
            <div>
                <div class="flex px-1 text-start justify-between">
                    <p class="text-xs text-slate-500">
                        Menampilkan
                        {{ $paginator->firstItem() ?? 0 }}
                        -
                        {{ $paginator->lastItem() ?? 0 }}
                        dari
                        {{ $paginator->total() }}
                        hasil
                    </p>
                    <p class="text-xs font-semibold text-slate-700">
                        Halaman {{ $paginator->currentPage() }}
                        dari {{ $paginator->lastPage() }}
                    </p>
                </div>
                <div class="mt-2.5 flex items-center justify-between gap-2">
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center p-2 text-sm font-medium text-slate-600 bg-[#1E3A8A]/5 border border-slate-200 cursor-not-allowed leading-5 rounded-lg">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex gap-2 items-center pl-2 pr-3 py-2 text-sm font-medium text-white bg-[#1E3A8A] border border-slate-200 leading-5 rounded-lg active:bg-[#88A4F4]/20 active:text-[#1E3A8A] transition ease-in-out duration-150">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            Sebelumnya
                        </a>
                    @endif
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex gap-2 items-center pr-2 pl-3 py-2 text-sm font-medium text-white bg-[#1E3A8A] border border-slate-200 leading-5 rounded-lg active:bg-[#88A4F4]/20 active:text-[#1E3A8A] transition ease-in-out duration-150">
                            Berikutnya
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    @else
                        <span class="inline-flex items-center p-2 text-sm font-medium text-slate-600 bg-[#1E3A8A]/5 border border-slate-200 cursor-not-allowed leading-5 rounded-lg">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm px-1 text-slate-500 leading-5">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-slate-600">{{ $paginator->firstItem() }}</span>
                        -
                        <span class="font-semibold text-slate-600">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-semibold text-slate-600">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>
            <div>
                <span class="inline-flex rtl:flex-row-reverse gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center px-2 py-2 text-slate-500 bg-[#1E3A8A]/5 border border-slate-200 cursor-not-allowed rounded-lg leading-5" aria-hidden="true">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-1 py-1 text-white bg-[#1E3A8A] border border-slate-200 rounded-lg leading-5 hover:text-[#1E3A8A] hover:bg-[#88A4F4]/20 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </a>
                    @endif
                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-slate-700 bg-white border border-slate-200 cursor-default leading-5 rounded-lg">{{ $element }}</span>
                            </span>
                        @endif
                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-slate-500 bg-[#F0F3FA] border border-slate-200 cursor-default leading-5 rounded-lg">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-slate-700 bg-white border border-slate-200 leading-5 hover:text-[#1E3A8A] hover:bg-[#88A4F4]/20 transition ease-in-out duration-150 rounded-lg" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-1 py-1 text-white bg-[#1E3A8A] border border-slate-200 rounded-lg leading-5 hover:text-[#1E3A8A] hover:bg-[#88A4F4]/20 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center px-2 py-2 text-slate-500 bg-[#1E3A8A]/5 border border-slate-200 cursor-not-allowed rounded-lg leading-5" aria-hidden="true">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
