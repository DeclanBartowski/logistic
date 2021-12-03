@extends('layouts.app')
@section('content')
    <div class="flex">
        <div class="page-left-col">
            <div class="about-page">
                <h1>{{$blog->name}}</h1>
                {!! $blog->detail_text !!}
            </div>
            <x-commerce-block type="mob"/>
            <x-vacancy-block/>
        </div>
        <div class="page-right-col">
            <x-side-text/>
            <x-commerce-block />
        </div>
    </div>
@endsection
