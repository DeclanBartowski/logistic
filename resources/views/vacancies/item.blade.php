<div class="item">
    <div class="company">
        <div class="info">
            @if(isset($vacancy->active_from) && $vacancy->active_from)
                <div class="date">
                    {{\Carbon\Carbon::parse($vacancy->active_from)->translatedFormat('d F')}}
                </div>
            @endif
            @if(isset($vacancy->preview_picture) && $vacancy->preview_picture)
                <div class="logo">
                    <img src="{{asset($vacancy->preview_picture)}}" alt="{{$vacancy->name??''}}">
                </div>
            @endif
            @if(isset($vacancy->link) && $vacancy->link)
                <a target="_blank"
                   href="{{$vacancy->link}}">{{str_replace(['http://','https://','//'],'',$vacancy->link)}}</a>
            @endif
        </div>
    </div>
    @if(isset($vacancy->city) && $vacancy->city)
        <div class="city">
            {{$vacancy->city}}
        </div>
    @endif
    <a href="{{route('vacancies.show',$vacancy)}}" class="name">{{$vacancy->name??''}}</a>
    @if(isset($vacancy->schedule) && $vacancy->schedule)
        <div class="hours">
            {{$vacancy->schedule}}
        </div>
    @endif
    @if(isset($vacancy->wage) && $vacancy->wage)
        <div class="price">
            {{__('vacancies.wage',['wage'=>number_format($vacancy->wage,'0',',',' ')])}}
        </div>
    @endif
    @if(isset($vacancy->preview_text) && $vacancy->preview_text)
        <p>{!! $vacancy->preview_text !!}</p>
    @endif
</div>
