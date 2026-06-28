@php
    $hasPages = $paginator->hasPages();
@endphp

<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
    <div class="flex items-center justify-end gap-1">

        {{-- Previous Page Link --}}
        @if (!$hasPages || $paginator->onFirstPage())
            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <span class="w-10 h-10 flex items-center justify-center text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg" aria-hidden="true">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 flex items-center justify-center text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-[#C41E3A] hover:border-red-200 transition" aria-label="{{ __('pagination.previous') }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        @if ($hasPages)
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="w-10 h-10 flex items-center justify-center text-base font-medium text-gray-400 cursor-default">{{ $element }}</span>
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="w-10 h-10 flex items-center justify-center text-base font-semibold text-white bg-[#C41E3A] rounded-lg cursor-default">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center text-base font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-[#C41E3A] hover:border-red-200 transition" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @else
            {{-- Cuma 1 halaman: tetap tampilkan nomor "1" aktif --}}
            <span aria-current="page">
                <span class="w-10 h-10 flex items-center justify-center text-base font-semibold text-white bg-[#C41E3A] rounded-lg cursor-default">1</span>
            </span>
        @endif

        {{-- Next Page Link --}}
        @if ($hasPages && $paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 flex items-center justify-center text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-[#C41E3A] hover:border-red-200 transition" aria-label="{{ __('pagination.next') }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @else
            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <span class="w-10 h-10 flex items-center justify-center text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg" aria-hidden="true">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </span>
        @endif

    </div>
</nav>