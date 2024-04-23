@if ($paginator->hasPages())
    <nav aria-label="Page navigation example" style="text-align: center">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <!--<li class="page-item"><span class="page-link">← Previous</span></li>-->
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a></li>
            @endif

            @php $iter = 0; @endphp
            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                            @php if($iter > 4) break; $iter++ @endphp
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active my-active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            @php
                            if ($page == 1) {
                                $url = str_replace('?page=1', '', $url);
                            }
                            @endphp
                            <li class="page-item">
                                <a title="Страница № {{ $page }}" class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Далее →</a></li>
            @else
                <li class="disabled"><span class="page-link">Next →</span></li>
            @endif
        </ul>
    </nav>
@endif

