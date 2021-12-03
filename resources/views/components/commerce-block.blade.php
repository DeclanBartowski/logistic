@if(isset($items) && $items)
    <div class="banners-block {{$type == 'mob'?'mob':''}}">
        @foreach($items as $item)
            <div class="banner">
                <img alt="" src="{{asset($item->picture)}}">
                <a target="_blank" href="{{$item->link}}"></a>
            </div>
        @endforeach
    </div>
@endif
