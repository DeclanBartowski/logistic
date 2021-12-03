<!DOCTYPE html>
<html lang="ru">
<head>
    {!! SEOMeta::generate() !!}
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('/img/favicon.ico')}}" />
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <link rel="stylesheet" href="{{asset('costume.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{asset('jquery.formstyler.css')}}" rel="stylesheet" />

    <script src="http://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{asset('js/common.js')}}"></script>
    <script src="{{asset('js/jquery.formstyler.min.js')}}"></script>
    <script src="{{asset('js/jquery.maskedinput.min.js')}}" defer></script>
    {!! NoCaptcha::renderJs() !!}
    <script src="{{asset('js/costume.js')}}"></script>
</head>
<body>
@yield('content')
<script>
    $(document).ajaxStop(function () {
        grecaptcha.reset();
    });
</script>
</body>
</html>
