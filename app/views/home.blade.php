@extends('layout')

@section('title')
| {{ trans('airsoft.home.title') }}
@stop

@section('header')
{{ HTML::style('/css/home.css') }}
<link rel="prefetch" href="{{ URL::route('games') }}" />
<link rel="prerender" href="{{ URL::route('games') }}" />
@stop

@section('content')
<table id="about-table">
    <tr>
        <td>
            <h3>{{ trans('airsoft.home.ad.o0') }}</h3>
            <ul>
                <li>{{ trans('airsoft.home.ad.o1') }}</li>
                <li>{{ trans('airsoft.home.ad.o2') }}</li>
                <li>{{ trans('airsoft.home.ad.o3') }}</li>
                <li>{{ trans('airsoft.home.ad.o4') }}</li>
                <li>{{ trans('airsoft.home.ad.o5') }}</li>
            </ul>
        </td>
        <td>
            <h3>{{ trans('airsoft.home.ad.p0') }}</h3>
            <ul>
                <li>{{ trans('airsoft.home.ad.p1') }}</li>
                <li>{{ trans('airsoft.home.ad.p2') }}</li>
                <li>{{ trans('airsoft.home.ad.p3') }}</li>
                <li>{{ trans('airsoft.home.ad.p4') }}</li>
                <li>{{ trans('airsoft.home.ad.p5') }}</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <div class="cell-dash @if(!Auth::check()) guest-hidden @endif">
                <a href="{{ URL::route('organizer-dashboard') }}">{{ trans('airsoft.home.dash-org') }}</a>
            </div>
        </td>
        <td>
            <div class="cell-dash @if(!Auth::check()) guest-hidden @endif">
                <a href="{{ URL::route('player-dashboard') }}">{{ trans('airsoft.home.dash-player') }}</a>
            </div>
        </td>
    </tr>
    @if(!Auth::check())
    <tr>
        <td colspan="2">
            <div id="sign-up-form">
                <div>
                    <input type="email" class="my-input email" autofocus placeholder="your email"/>
                    <button class="my-btn submit" onclick="signUp()">{{ trans('airsoft.home.sign-up') }}</button>
                </div>
            </div>
        </td>
    </tr>
    @endif
</table>
<script>
function signUp()
{
    var re = /\S+@\S+\.\S+/, email = $('#sign-up-form .email').val().trim()
    if (!re.test(email))
    {
        az.showModal('{{ trans('airsoft.home.non-email') }}', function()
        {
            $('.email').focus()
        })
        return false
    }

    az.ajaxVerbosity = 1
    az.ajaxPost('user', JSON.stringify({email:email,autologin:1}), function(data)
    {
        if (data)
        {
            az.showModal('{{ trans('airsoft.home.check-email') }}', function()
            {
                document.location.reload()
            })
        }
    })
}
$('#sign-up-form .email').keypress(function(e)
{
    if (e.keyCode == 13) signUp()
})
</script>
@stop