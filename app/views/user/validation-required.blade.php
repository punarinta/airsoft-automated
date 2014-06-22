@extends('layout')

@section('content')
<div class="dialog-box-1">
    <p>To protect players and their finances we need to validate all game organizers.</p>
    <p>Just write a couple of words about you as an organizer on <a target="_blank" href="mailto:{{ Config::get('app.emails.moderator') }}?subject=I am an organizer">{{ Config::get('app.emails.moderator') }}</a> email and we will answer you as soon as possible. :)</p>
    The system is now in development version, so there are <b>no charges</b> for the game management.
</div>
@stop