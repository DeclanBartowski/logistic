@if(isset($links) && $links)
<ul>
    @foreach($links as $link)
        <li><a href="{{$link->link}}" {{isset($link->current) && $link->current == 'Y'?'class=active':''}}>{{$link->name}}</a></li>
    @endforeach

</ul>
@endif
