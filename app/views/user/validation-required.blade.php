@extends('layout')

@section('content')
<div class="dialog-box-1">
    <p>To protect players and their finances we need to validate all game organizers.</p>
    <p>Just write a couple of words about you as an organizer on <a target="_blank" href="mailto:{{ Config::get('app.emails.moderator') }}?subject=I am an organizer">{{ Config::get('app.emails.moderator') }}</a> email and we will answer you as soon as possible. :)</p>
    Now we are in a development version, so <b>we are not charging anything</b> for the game management.
    But keep in mind that the payment service provider (PSP) will charge 2.95% + 3 SEK if you decide to allow card payments for your game participants.
</div>
@stop