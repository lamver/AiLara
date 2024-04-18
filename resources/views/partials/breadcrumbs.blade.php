@if (count($breadcrumbs))
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ $breadcrumb->url }}">{!! $breadcrumb->title !!}</a></li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{!! $breadcrumb->title !!}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
