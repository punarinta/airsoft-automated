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
        <ul style="float:left">
            <li><a class="first" href="{{ URL::route('home') }}">Home</a></li>
            <li><a href="{{ URL::route('games') }}">Games</a></li>
            @if(Auth::check())
            <li><a href="{{ URL::route('player-dashboard') }}">Player</a></li>
            <li><a href="{{ URL::route('organizer-dashboard') }}">Organizer</a></li>
            <li><a href="{{ URL::route('user-profile') }}">Settings</a></li>
            @endif
            <li><a class="last" href="{{ URL::route('about') }}">About</a></li>
        </ul>

        @if(Session::has('flash_notice'))
        <span class="flashbar" id="flash_notice">{{ Session::get('flash_notice') }}</span>
        @endif
        @if (Session::has('flash_error'))
        <span class="flashbar" id="flash_error">{{ Session::get('flash_error') }}</span>
        @endif

        <ul style="float:right">
            @if(Auth::check())
            <li><a class="alone" href="{{ URL::route('logout') }}">Sign out</a></li>
            @else
            <li><a class="alone" href="{{ URL::route('login') }}">Sign in</a></li>
            @endif
        </ul>
    </div>

    <div id="content">@yield('content')</div>
</div>
</body>
</html>