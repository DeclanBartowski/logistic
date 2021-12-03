@extends('layouts.guest')
@section('content')
        <div class="login-page">
            <form id="register" method="post" action="{{route('user-register')}}" @if(!old('code',''))data-check-url="{{route('check-phone')}}" @endif>
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
                @if(isset($siteSettings->logo_auth) && $siteSettings->logo_auth)
                    <div class="logo">
                        <a href="{{route('home')}}"><img alt="logo" src="{{asset($siteSettings->logo_auth)}}"></a>
                    </div>
                @endif
                <div class="block-name">
                    {{__('auth/register.title')}}
                </div>
                <div class="bottom-link other">
                    {!! __('auth/register.link',['link'=>route('login')]) !!}
                </div>
                <div class="field-name">
                    {{__('auth/register.phone')}}
                </div>
                <div class="field">
                    <input type="text" name="phone" class="phone-mask" value="{{old('phone','')}}" placeholder="+375 __-___-__-__">
                </div>
                <div class="field-name">
                    {{__('auth/register.email')}}
                </div>
                <div class="field">
                    <input type="text" name="email" value="{{old('phone','')}}" placeholder="example@gmail.com">
                </div>
                <div class="field-name">
                    {!! __('auth/register.password') !!}
                </div>
                <div class="field">
                    <input type="password" name="password" value="{{old('password','')}}">
                </div>
                <div class="field-name">
                    {{__('auth/register.password_confirm')}}
                </div>
                <div class="field">
                    <input type="password" name="password_confirmation" value="{{old('password_confirm','')}}">
                </div>
                <div class="captcha">
                    {!! app('captcha')->display() !!}
                </div>
                <div class="button">
                    <button>{{__('auth/register.submit')}}</button>
                </div>
                <div class="bottom-text">
                    {!! __('auth/register.agreement') !!}
                </div>
            </form>
        </div>
@endsection
