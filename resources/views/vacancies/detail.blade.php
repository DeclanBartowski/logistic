@extends('layouts.app')
@section('content')
    <div class="flex">
        <div class="page-left-col">
            <div class="about-page">
                <h1>{{$vacancy->name}}</h1>
                {!! $vacancy->detail_text !!}
            </div>
            <x-commerce-block type="mob"/>
            @if(isset($vacancy->related_items) && $vacancy->related_items)
                <div class="vacancies-block">
                    <div class="block-name">
                        {{__('vacancies.related_vacancies')}}
                    </div>
                    @each('vacancies.item', $vacancy->related_items, 'vacancy')
                    <div class="bottom-link">
                        <a href="{{route('vacancies.index')}}">{{__('vacancies.more_vacancies')}}</a>
                    </div>

                </div>
            @endif
        </div>
        <div class="page-right-col">
            <x-side-text/>
            <x-commerce-block />
            <x-publication-block/>
        </div>
    </div>
@endsection
