@extends('layouts.app')
@section('content')
    <div class="flex">
        <div class="page-left-col">
            <div class="blog-page">
                <div class="block-name">
                    {{__('blog.title')}}
                </div>
                @if(isset($items) && $items)
                    @each('blog.item', $items, 'item')
                    {{$items->onEachSide(2)->links()}}
                @endif
            </div>
            <x-commerce-block type="mob"/>
            <x-vacancy-block/>
        </div>
        <div class="page-right-col">
            <x-side-text/>
            <x-commerce-block/>
        </div>
    </div>
@endsection
