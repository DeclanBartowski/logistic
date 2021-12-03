@extends('layouts.app')
@section('content')
    <div class="flex">
        <div class="page-left-col">
            <div class="add-salary-block">
                <div class="block-name">
                    {{__('wages.filter_title')}}
                </div>
                <div class="top-text2">
                     <span>{{__('wages.founded_title')}}{{trans_choice('wages.founded',$count,['count'=>$count])}}</span>
                </div>
                @if(isset($settings->texts['filter_hint']) && $settings->texts['filter_hint'])
                <div class="help-text">
                    {!! __('wages.filter_hint') !!}
                    {!! $settings->texts['filter_hint'] !!}
                </div>
                @endif
                <form method="get" action="{{route('salary.filter-result')}}">
                    @csrf
               {!! $form !!}
                <div class="buttons">
                    <button>Смотреть</button> &nbsp;
                    <button onclick="location.href='{{route('salary.index')}}'" type="button">Сбросить ввод</button>
                </div>
                </form>
            </div>
            <x-commerce-block type="mob"/>
            <x-vacancy-block/>
        </div>
        <div class="page-right-col">
            <x-side-text/>
            <x-commerce-block/>
            <x-publication-block/>
        </div>
    </div>
@endsection
