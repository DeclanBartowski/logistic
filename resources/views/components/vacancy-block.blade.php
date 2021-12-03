@if(isset($items) && $items)
    <div class="vacancies-block">
        <div class="block-name">
            {{__('block_names.vacation_title')}}
        </div>
        @if(isset($items) && $items)
            @each('vacancies.item', $items, 'vacancy')
        @endif
        <div class="bottom-link">
            <a href="{{route('vacancies.index')}}">{{__('block_names.vacation_more')}}</a>
        </div>
    </div>
@endif
