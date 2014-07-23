@extends('layout')

@section('title')
| Home
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
            <h3>For organizers</h3>
            <ul>
                <li>Create a game in a couple of clicks</li>
                <li>Don't care about tickets</li>
                <li>Don't care about informing everyone</li>
                <li>Save time with automatic game check-in</li>
                <li>Analyse statistics</li>
            </ul>
        </td>
        <td>
            <h3>For players</h3>
            <ul>
                <li>Manage your team</li>
                <li>Safely book tickets</li>
                <li>Pay in cash or wireless</li>
                <li>Receive all logistics via email</li>
                <li>Enjoy automatic game check-in</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <div @if(!Auth::check()) class="guest-hidden" @endif>
                <a href="{{ URL::route('organizer-dashboard') }}">Organizer dashboard</a>
            </div>
        </td>
        <td>
            <div @if(!Auth::check()) class="guest-hidden" @endif>
                <a href="{{ URL::route('player-dashboard') }}">Player dashboard</a>
            </div>
        </td>
    </tr>
    @if(!Auth::check())
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" id="sign-up-form">
            <input type="email" class="my-input email" autofocus placeholder="your email"/>
            <button class="my-btn submit" onclick="signUp()">Sign-up now!</button>
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
        az.showModal('Sorry, that´s not an email.', function()
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
            az.showModal('Thanks. Check your email for a password.', function()
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