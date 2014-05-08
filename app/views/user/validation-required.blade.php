@extends('layout')

@section('content')

<!--  Keep this view for some time  -->

<div class="dialog-box-1">
To protect players and their finances we need to validate game organizers. Just write a couple of words about you as an organizer on <a target="_blank" href="mailto:{{ Config::get('app.emails.support') }}?subject=I am an organizer">{{ Config::get('app.emails.support') }}</a> email and we will answer ASAP.
</div>

@stop