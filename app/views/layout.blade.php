<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Laravel Demo</title>
    {{ HTML::style('/css/common.css') }}
    @yield('header')
    <script src="http://use.edgefonts.net/source-sans-pro:n3,i3,n4,i4,n6,i6,n7,i7.js"></script>
    <script src="http://use.edgefonts.net/source-code-pro.js"></script>
</head>
<body>
<div id="main">
    <div id="navbar">
        <ul>
            <li><a href="{{ URL::route('home') }}">Home</a></li>
            @if(Auth::check())
            <li><a href="{{ URL::route('user-profile') }}">Profile</a></li>
            <li><a href="{{ URL::route('logout') }}">Logout ({{ Auth::user()->email }})</a></li>
            @else
            <li><a href="{{ URL::route('login') }}">Login</a></li>
            @endif
        </ul>
    </div>

    @if(Session::has('flash_notice'))
    <div id="flash_notice">{{ Session::get('flash_notice') }}</div>
    @endif

    <div id="content">@yield('content')</div>
</div>
</body>
</html>