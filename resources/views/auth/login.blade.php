@extends('layouts.guest')
@section('content')
    <div class="login-page">
        <form action="{{route('user-login')}}" method="post">
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
            @if(session('status'))
                <div class="tq_success">
                {{session('status')}}
                </div>
            @endif
            @if(isset($siteSettings->logo_auth) && $siteSettings->logo_auth)
            <div class="logo">
                <a href="{{route('home')}}"><img alt="logo" src="{{asset($siteSettings->logo_auth)}}"></a>
            </div>
            @endif
            <div class="block-name">
                {{__('auth/login.title')}}
            </div>
            <div class="field-name">
                {{__('auth/login.login')}}
            </div>
            <div class="field">
                <input type="text" name="login" placeholder="example@gmail.com" tabindex="1" value="{{old('login')}}">
            </div>
            <div class="field-name">
                {!! __('auth/login.password',['link'=>route('forget-password')]) !!}
            </div>
            <div class="field">
                <input type="password" name="password" tabindex="2">
            </div>
            <div class="captcha">
                {!! app('captcha')->display() !!}
            </div>
            <div class="button">
                <button>{{__('auth/login.submit')}}</button>
            </div>
            <div class="bottom-link">
                {!! __('auth/login.register',['link'=>route('registration')]) !!}
            </div>
        </form>
    </div>
@endsection
