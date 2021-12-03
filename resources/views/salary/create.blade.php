@extends('layouts.app')
@section('content')
    {!! NoCaptcha::renderJs('ru') !!}
    <div class="flex">
        <div class="page-left-col">
            <div class="add-salary-block">
                @if(isset($userWage) && $userWage)
                        <p class="top-text">{{__('wages.edit_text',['date'=>date('d F Y',strtotime($userWage->updated_at))])}}</p>
                @endif
            <form method="post" action="{{route('salary.store')}}">
                @csrf
                @if ($errors->any())
                    <div class="tq_errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="block-name">
                    Добавить свою зарплату
                </div>
                <div class="top-text">
                    Мы гарантируем полную приватность ваших данных по зарплате.
                </div>
                {!! $form !!}

                <div class="captcha">
                    {!! app('captcha')->display() !!}
                </div>
                <button>Сохранить</button>
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
