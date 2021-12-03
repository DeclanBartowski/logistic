@if(isset($sponsors) && $sponsors)
    <div class="index-top-logos">
        <div class="flex">
            @foreach($sponsors as $sponsor)
                <div class="item">
                    <img alt="" src="{{asset($sponsor->logo)}}">
                </div>
            @endforeach
        </div>
    </div>
@endif
