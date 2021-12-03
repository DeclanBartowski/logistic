@extends('layouts.app')
@section('content')
    <div class="flex">
        <div class="page-left-col">
            <div class="vacancies-block">
                <form>
                    @csrf
                    <h1>{{__('vacancies.title')}}</h1>
                    <div class="flex">
                        <input type="text" name="q" value="{{$query??''}}" placeholder="{{__('vacancies.search_placeholder')}}">
                        <button>{{__('vacancies.search_btn')}}</button>
                    </div>
                    <p>{{trans_choice('vacancies.found_cnt', isset($vacancies)?$vacancies->count():0, ['cnt' => number_format(isset($vacancies)?$vacancies->count():0,'0',',',' ')])}}</p>
                </form>
                @if(isset($vacancies) && $vacancies)
                     @each('vacancies.item', $vacancies, 'vacancy')
                {{$vacancies->onEachSide(2)->links()}}
                @endif
            </div>
            <x-commerce-block type="mob"/>
        </div>
        <div class="page-right-col">
            <x-side-text/>
            <x-commerce-block/>
            <x-publication-block/>
        </div>
    </div>
@endsection
