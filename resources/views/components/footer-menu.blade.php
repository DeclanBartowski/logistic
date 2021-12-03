@if(isset($links) && $links)
    @foreach($links as $arGroup)
<nav>
    <p>{{$arGroup['name']}}</p>
    @if(isset($arGroup['items']) && $arGroup['items'])
    <ul>
        @foreach($arGroup['items'] as $arGroup)
        <li><a href="{{$arGroup['link']}}">{{$arGroup['name']}}</a></li>
        @endforeach
    </ul>
    @endif
</nav>
    @endforeach
@endif
