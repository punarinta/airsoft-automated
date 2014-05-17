@extends('layout')

@section('content')
<div class="dialog-box-1">
    <p>Ticket issued. Thanks for your participation.</p>
    <p>
        You may proceed to <a href="{{ URL::route('game-briefing', $game_id) }}">game briefing</a>
        to get your game party specific information and print your ticket.
    </p>
</div>
@stop