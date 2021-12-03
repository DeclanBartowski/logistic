
@if(isset($elements) && $paginator->hasPages())
<div class="pages">
    <!-- Ссылка на предыдущую страницу -->
    @if (!$paginator->onFirstPage())
        <a href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
    @endif
<!-- Элементы страничной навигации -->
    @foreach ($elements as $element)
    <!-- Разделитель "Три точки" -->
        @if (is_string($element))
            <a>{{ $element }}</a>
        @endif
    <!-- Массив ссылок -->
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <a class="active">{{ $page }}</a>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach
<!-- Ссылка на следующую страницу -->
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
    @endif
</div>
@endif
