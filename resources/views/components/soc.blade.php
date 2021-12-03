@if(isset($items) && $items)
<div class="social">
    @foreach($items as $item)
    <a href="{{asset($item->link)}}" target="_blank"><img alt="{{$item->name}}" src="{{asset($item->picture)}}"></a>
    @endforeach
</div>
@endif
