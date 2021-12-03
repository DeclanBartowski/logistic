@if(isset($banner) && $banner)

    <div class="index-first-block">
        @if(isset($banner->name) && $banner->name)
            <div class="b1">
                {{$banner->name}}
            </div>
        @endif
        @if(isset($banner->description) && $banner->description)
            <div class="b2">
                {!! $banner->description !!}
            </div>
        @endif
        @if(isset($banner->mob_picture) && $banner->mob_picture)
            <div class="mob-image">
                <img src="{{asset($banner->mob_picture)}}" alt="{{$banner->name??''}}">
            </div>
        @endif
        @if(isset($banner->text) && $banner->text)
            <div class="b3">
                {!! $banner->text !!}
            </div>
        @endif
        @if(isset($banner->wages_title) && $banner->wages_title)
            <button @if(isset($banner->url) && $banner->url) onclick="location.href='{{$banner->url}}'" @endif>{!! $banner->wages_title !!}</button>
        @endif
        @if((isset($banner->wages) && $banner->wages) ||(isset($banner->picture) && $banner->picture))
            <div class="image">
                @if(isset($banner->picture) && $banner->picture)
                    <img src="{{asset($banner->picture)}}" alt="{{$banner->name??''}}">
                @endif
                @if(isset($banner->wages) && $banner->wages)
                    @foreach($banner->wages as $key=> $wage)
                        <div class="item i{{$key+1}}">
                            @if(is_array($wage))
                                {!! reset($wage)??'' !!}
                            @else
                                {!! $wage->wages??'' !!}
                            @endif

                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>
@endif
