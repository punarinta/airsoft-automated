@extends('layout')

@section('header')
<style>
#ticket {border:1px solid #ddd;border-radius:3px}
</style>
@stop

@section('content')

<div class="window-box-1">
    <p>
        No specific information present on this game.
        <br/>
        You can print your ticket and you're done.
    </p>
    <img id="ticket" src="{{ URL::route('game-ticket', array($game_id)) }}" alt="Your ticket"/>
    <br/><br/>
    <button id="btn-print-ticket" class="my-btn">Print ticket</button>
</div>
<script>
$('#btn-print-ticket').click(function()
{
    var w = window.open('', 'Ticket printer', 'height=250,width=550')
    w.document.write('<html><head><title>Ticket printer</title></head><body><img id="ticket" src="{{ URL::route('game-ticket', array($game_id)) }}"/></body></html>')
    w.print()
})
</script>
@stop