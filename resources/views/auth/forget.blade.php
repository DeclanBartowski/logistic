@extends('layouts.guest')
@section('content')
    <div class="login-page">
        <form action="{{route('user-forget')}}" method="post">
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
                <a href="{{route('home')}}"><img src="{{asset($siteSettings->logo_auth)}}"></a>
            </div>
            @endif
            <div class="block-name">
                {{__('auth/forget.title')}}
            </div>
            <div class="field-name">
                {{__('auth/forget.login')}}
            </div>
            <div class="field">
                <input type="text" name="login" placeholder="example@gmail.com" value="{{old('login')}}">
            </div>
            <div class="captcha">
                {!! app('captcha')->display() !!}
            </div>
            <div class="button">
                <button>{{__('auth/forget.submit')}}</button>
            </div>
            <div class="bottom-link">
                {!! __('auth/forget.register',['link'=>route('registration')]) !!}
            </div>
        </form>
    </div>
@endsection
