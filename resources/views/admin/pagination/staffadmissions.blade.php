@if ($paginator->hasPages())
    <ul class="pagination pagination-sm no-margin pull-right">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li>
                @php
                    $previousPageUrl = $paginator->previousPageUrl();
                    $urlComponents = parse_url($previousPageUrl);
                    $queryParams = [];
                    if (isset($urlComponents['query'])) {
                        parse_str($urlComponents['query'], $queryParams);
                    }
                    $previousPageNumber = $queryParams['page'] ?? null;
                @endphp



                <a href="jsvascript:void(0)"
                    onclick="get_students(
        {{$adminId}},
        '{{ $previousPageNumber }}',
        document.getElementById('keyword').value,
        document.getElementById('class_id').value)"
                    rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="javascript:void(0)"
                                onclick="get_students(
                            {{$adminId}},
                            '{{ $page }}',
                            document.getElementById('keyword').value,
                            document.getElementById('class_id').value)">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                @php
                    $nextPageUrl = $paginator->nextPageUrl();
                    $urlComponents = parse_url($nextPageUrl);
                    $queryParams = [];
                    if (isset($urlComponents['query'])) {
                        parse_str($urlComponents['query'], $queryParams);
                    }
                    $nextPageNumber = $queryParams['page'] ?? null;
                @endphp

                <a href="javascript:void(0)"
                    onclick="get_students(
                    {{$adminId}},
                    '{{ $nextPageNumber }}',
                    document.getElementById('keyword').value,
                    document.getElementById('class_id').value)"
                    rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">&rsaquo;</span>
            </li>
        @endif
    </ul>
@endif
