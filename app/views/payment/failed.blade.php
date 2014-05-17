@extends('layout')

@section('content')
<div class="dialog-box-1">
    <p>Payment provider was not able to process your payment and returned code "{{ @$response_text }}".</p>
    <p>
        We kindly ask you to <a href="{{ URL::route('game-book', $game_id) }}">try again</a> and if the problem still stays contact us via
        <a href="mailto:{{ Config::get('app.emails.support') }}?subject=Payment error">{{ Config::get('app.emails.support') }}</a>.
    </p>
</div>
@stop