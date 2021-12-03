@props(['status'])

@if ($status)
    <div class="notify-block success">
       <span> {{ $status }}</span>
    </div>
@endif
