<!DOCTYPE html>
<html lang="ru">
<head>
    {!! SEOMeta::generate() !!}
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="shortcut icon" href="{{asset('/img/favicon.ico')}}" />
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <link rel="stylesheet" href="{{asset('jquery.formstyler.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="{{asset('js/jquery-3.4.1.min.js')}}" defer></script>
    <script src="{{asset('js/common.js')}}" defer></script>
    <script src="{{asset('js/jquery.maskedinput.min.js')}}" defer></script>
    <script src="{{asset('js/jquery.formstyler.min.js')}}" defer></script>
    <script src="{{asset('js/costume.js')}}" defer></script>
</head>
<body>
<header class="header">
    <div class="wrap flex">
        @if(App\Facade\SiteSetting::getField('logo'))
            <div class="logo">
                <a href="/"><img alt="logisticsalaries" src="{{asset(App\Facade\SiteSetting::getField('logo'))}}"></a>
            </div>
        @endif
            @if(App\Facade\SiteSetting::getText('top_text'))
                <p>{!! App\Facade\SiteSetting::getText('top_text') !!}</p>
            @endif

        @if(Auth::check())
            <div class="user-block">
                <div class="buttons" style="display: flex;">
                    <a href="javascript:void(0)"></a>
                    <button onclick="location.href='{{route('logout')}}'" style="margin-left: 5px;"><span></span> Выход</button>
                </div>
            </div>
        @else
            <div class="buttons">
                <button onclick="location.href='{{route('registration')}}'"><span></span> Регистрация</button>
                <button onclick="location.href='{{route('login')}}'"><span></span> Вход</button>
            </div>
        @endif
    </div>
</header>
<nav class="top-menu">
    <div class="wrap flex">
        <x-header-menu/>
        @if(Auth::check())
            <div class="user-block">
                <div class="buttons" style="display: flex;">
                    <a href="javascript:void(0)"></a>
                    <button onclick="location.href='{{route('logout')}}'" style="margin-left: 5px;"><span></span> Выход</button>
                </div>
            </div>
        @else
            <div class="buttons">
                <button onclick="location.href='{{route('registration')}}'"><span></span> Регистрация</button>
                <button onclick="location.href='{{route('login')}}'"><span></span> Вход</button>
            </div>
        @endif
    </div>
</nav>
<div class="wrap">
    <x-header-banner/>
    <x-sponsors/>
    <div id="content"></div>
    @yield('content')
</div>
<footer class="footer">
    <div class="wrap">
        <div class="flex">
            <div class="logo">
                @if(App\Facade\SiteSetting::getField('logo'))
                <a href="/"><img alt="logisticsalaries" src="{{asset(App\Facade\SiteSetting::getField('logo'))}}"></a>
                @endif
                <x-social/>

            </div>
            <x-footer-menu/>

            @if(App\Facade\SiteSetting::getText('contacts'))
            <div class="contacts">
                {!! App\Facade\SiteSetting::getText('contacts') !!}
            </div>
            @endif
        </div>
    </div>
    <div class="bottom-line">
        <div class="wrap flex">
            <x-social/>
            @if(App\Facade\SiteSetting::getText('link'))
                {!! App\Facade\SiteSetting::getText('link') !!}
            @endif
            @if(App\Facade\SiteSetting::getText('copyright'))
                <p>{!! App\Facade\SiteSetting::getText('copyright') !!}</p>
            @endif

        </div>
    </div>
</footer>
@if(\Illuminate\Support\Facades\Route::current()->getName() !='home')
    <script>
        document.addEventListener("DOMContentLoaded", function (){
            $('html,body').animate({scrollTop: $('#content').offset().top});
        });
    </script>
@endif
</body>
</html>
