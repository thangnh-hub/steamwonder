@if ($paginator->hasPages())
    <div class="row">
        <div class="col">
            <div class="news_pagination mb-3">
                <ul>
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li><a href="#" onclick="event.preventDefault();"><i class="fa fa-arrow-left"
                                    aria-hidden="true"></i></a></li>
                    @else
                        <li><a href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-arrow-left"
                                    aria-hidden="true"></i></a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li><a href="#" onclick="event.preventDefault();">{{ $element }}</a>
                            </li>
                        @endif
                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active"><a href="#"
                                            onclick="event.preventDefault();">{{ $page }}</a></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li><a href="{{ $paginator->nextPageUrl() }}"><i class="fa fa-arrow-right"
                                    aria-hidden="true"></i></a></li>
                    @else
                        <li><a href="#" onclick="event.preventDefault();"><i class="fa fa-arrow-right"
                                    aria-hidden="true"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endif
