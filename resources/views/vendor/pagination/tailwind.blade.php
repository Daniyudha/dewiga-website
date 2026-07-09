@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-4">
        {{-- Mobile View --}}
        <div class="flex justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 cursor-default leading-5 rounded-lg">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors">
                    Selanjutnya &raquo;
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 cursor-default leading-5 rounded-lg">
                    Selanjutnya &raquo;
                </span>
            @endif
        </div>

        {{-- Desktop View: Kiri - Info, Kanan - Tombol --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between w-full">
            {{-- Kiri: Info data --}}
            <div class="text-left">
                <p class="text-sm text-gray-600">
                    @if ($paginator->firstItem())
                        Menampilkan
                        <span class="font-semibold text-primary-700">{{ $paginator->firstItem() }}</span>
                        s/d
                        <span class="font-semibold text-primary-700">{{ $paginator->lastItem() }}</span>
                        dari
                        <span class="font-bold text-primary-700">{{ $paginator->total() }}</span>
                        data
                    @else
                        {{ $paginator->count() }} data
                    @endif
                </p>
            </div>

            {{-- Kanan: Tombol halaman --}}
            <div class="flex items-center gap-1">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-lg leading-5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg leading-5 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 transition-colors" aria-label="Halaman sebelumnya">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Nomor Halaman --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-500 bg-white border border-gray-200 cursor-default rounded-lg leading-5">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-primary-600 border border-primary-600 cursor-default rounded-lg leading-5 shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg leading-5 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 transition-colors" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg leading-5 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 transition-colors" aria-label="Halaman selanjutnya">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-lg leading-5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif