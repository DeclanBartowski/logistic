@extends('layouts.app')
@section('content')
    <div class="flex">
        <div class="page-left-col">
            <div class="about-page">
                @if(isset($page->name) && $page->name)
                    <h1>{{$page->name}}</h1>
                @endif
                {!! $page->text??'' !!}
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
