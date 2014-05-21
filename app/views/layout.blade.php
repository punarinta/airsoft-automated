<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Airsoft Zone</title>
    {{ HTML::style('/css/common.css') }}
    @yield('header')
    <script src="//use.edgefonts.net/source-sans-pro:n3,i3,n4,i4,n6,i6,n7,i7.js"></script>
    <script src="//use.edgefonts.net/source-code-pro.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/jquery-2.1.1.min.js"><\/script>')</script>
    <script src="/js/az-core.js"></script>
    <link rel="icon" href="/favicon.ico" />
</head>
<body>
<div id="modal-background">
    <div id="modal">
        <div class="content"></div>
        <hr class="my-hr"/>
        <button class="my-btn">OK</button>
    </div>
</div>
<div id="main">
    <div id="navbar">
        <ul style="float:left">
            <li><a class="first" href="{{ URL::route('home') }}">Home</a></li>
            <li><a href="{{ URL::route('games') }}">Games</a></li>
            @if(Auth::check())
            <li><a href="{{ URL::route('player-dashboard') }}">I play</a></li>
            <li><a href="{{ URL::route('organizer-dashboard') }}">I organize</a></li>
            <li><a href="{{ URL::route('user-profile') }}">Profile</a></li>
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
            <li><a class="alone" href="{{ URL::route('logout') }}">Sign out ({{ Auth::user()->nick ? Auth::user()->nick : 'no nick' }})</a></li>
            @else
            <li><a class="alone" href="{{ URL::route('login') }}">Sign in</a></li>
            @endif
        </ul>
    </div>

    <div id="content">@yield('content')</div>
</div>
</body>
</html>