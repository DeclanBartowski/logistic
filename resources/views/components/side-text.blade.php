@if(isset($page) && $page)
<div class="about-service-block">
    <div class="name">
        {{$page->name??''}}
    </div>
    @if(isset($page->text) && $page->text)
    <div class="text">
        {!! $page->text !!}
    </div>
    @endif
</div>
@endif
