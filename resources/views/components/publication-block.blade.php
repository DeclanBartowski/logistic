@if(isset($items) && $items)
    <div class="articles-block">
        <div class="block-name">
            {{__('block_names.blog_title')}}
        </div>
        @foreach($items as $item)
        <div class="item">
            @if(isset($item->preview_picture) && $item->preview_picture)
                <img src="{{asset($item->preview_picture)}}" alt="{{$item->name??''}}">
            @endif
            <a href="{{route('blog.show',$item)}}" class="name">{{$item->name??''}}</a>
                @if(isset($item->tag) && $item->tag)
            <a href="{{route('blog.show',$item)}}" class="tag">{{$item->tag??''}}</a>
                @endif
        </div>
        @endforeach
        <div class="bottom-link">
            <a href="{{route('blog.index')}}">{{__('block_names.blog_more')}}</a>
        </div>
    </div>
@endif
