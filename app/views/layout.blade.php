<!doctype html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>Airsoft Zone @yield('title')</title>
    <meta name="description" content="Airsoft automation system">
    <meta name="keywords" content="airsoft,game,automation">
    <meta name="viewport" content="width=device-width, initial-scale=0.7">
    <link rel="icon" href="/favicon.ico">
    {{ HTML::style('/css/common.css') }}
    @yield('header')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/jquery-2.1.1.min.js"><\/script>')</script>
    <script src="/js/az-core.min.js"></script>
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
            <li><a class="first" href="{{ URL::route('home') }}">{{ trans('airsoft.menu.home') }}</a></li>
            <li><a href="{{ URL::route('games') }}">{{ trans('airsoft.menu.games') }}</a></li>
            @if(Auth::check())
            <li><a href="{{ URL::route('player-dashboard') }}">{{ trans('airsoft.menu.i_play') }}</a></li>
            <li><a href="{{ URL::route('organizer-dashboard') }}">{{ trans('airsoft.menu.i_organize') }}</a></li>
            <li><a href="{{ URL::route('user-profile') }}">{{ trans('airsoft.menu.profile') }}</a></li>
            @endif
            <li><a href="{{ URL::route('extra') }}">{{ trans('airsoft.menu.extra') }}</a></li>
            <li><a class="last" href="{{ URL::route('about') }}">{{ trans('airsoft.menu.about') }}</a></li>
        </ul>

        @if(Session::has('flash_notice'))
        <span class="flashbar" id="flash_notice">{{ Session::get('flash_notice') }}</span>
        @endif
        @if (Session::has('flash_error'))
        <span class="flashbar" id="flash_error">{{ Session::get('flash_error') }}</span>
        @endif

        <ul style="float:right">
<!--            <li><a class="first" href="#" style="padding-top:0">
                <select class="ddb-locale my-select">
                    <option value="en">EN</option>
                    <option value="se">SE</option>
                </select>
            </a></li>-->

            @if(Auth::check())
            <li><a class="alone" href="{{ URL::route('logout') }}">{{ trans('airsoft.menu.sign_out') }} ({{ Auth::user()->nick ? Auth::user()->nick : trans('airsoft.menu.no_nick') }})</a></li>
            @else
            <li><a class="alone" href="{{ URL::route('login') }}">{{ trans('airsoft.menu.sign_in') }}</a></li>
            @endif
        </ul>
    </div>

    <div id="content">@yield('content')</div>
</div>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-51418351-1', 'airsoft.zone');
ga('require', 'displayfeatures');
ga('send', 'pageview');

$('#navbar .ddb-locale').val('{{ App::getLocale() }}').change(function(){$.ajax({url:'/api/session',type:'PUT',dataType:'json',data:JSON.stringify({locale:$('#navbar .ddb-locale').val()}),success:function(){location.reload()}})})
</script>
<script src="//use.edgefonts.net/source-sans-pro:n3,i3,n4,i4,n6,i6,n7,i7.js"></script>
<script src="//use.edgefonts.net/source-code-pro.js"></script>
<a href="https://metrica.yandex.com/stat/?id=26981409&amp;from=informer" target="_blank" rel="nofollow"><img style="display:none" src="//bs.yandex.ru/informer/26981409/3_1_FFFFFFFF_EFEFEFFF_0_pageviews" style="width:88px; height:31px; border:0;" alt="Yandex.Metrica" title="Yandex.Metrica: data for today (page views, visits and unique visitors)" onclick="try{Ya.Metrika.informer({i:this,id:26981409,lang:'en'});return false}catch(e){}"/></a><!-- /Yandex.Metrika informer --><!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter26981409 = new Ya.Metrika({id:26981409, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/26981409" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
</body>
</html>