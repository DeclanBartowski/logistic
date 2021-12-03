<div class="item">
    <div class="flex">
        @if(isset($item->link) && $item->link)
            <div class="site">
                <a target="_blank"
                   href="{{$item->link}}">{{str_replace(['http://','https://','//'],'',$item->link)}}</a>
            </div>
        @endif

        @if(isset($item->active_from) && $item->active_from)
            <div class="date">
                {{\Carbon\Carbon::parse($item->active_from)->translatedFormat('d F')}}
            </div>
        @endif
    </div>
    <a href="{{route('blog.show',$item)}}" class="name">{{$item->name??''}}</a>
    @if(isset($item->preview_text) && $item->preview_text)
        <p>{!! $item->preview_text !!}</p>
    @endif

    @if(isset($item->preview_picture) && $item->preview_picture)
        <img src="{{asset($item->preview_picture)}}" alt="{{$item->name??''}}">
    @endif
    <button onclick="location.href='{{route('blog.show',$item)}}'">{{__('blog.read_more')}}</button>
</div>

