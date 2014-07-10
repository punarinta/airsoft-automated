@extends('layout')

@section('title')
| Specific game info
@stop

@section('header')
<style>
#ticket {border:1px solid #ddd;border-radius:3px}
#bank-data {padding-bottom:30px}
</style>
@stop

@section('content')

<div class="window-box-1">
    <p>
        No specific information present on this game.
        <br/>
        @if (!($data->status & Ticket::STATUS_PAID))
        Your ticket is booked but not paid.
        <div id="bank-data">
            <fieldset class="my-fieldset">
                <legend>Payment information:</legend>
                <table>
                    <tr>
                        <td>Account:</td>
                        <td><b>5699 3477637</b></td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td><b>{{ number_format($data->price / 100, 2) }} SEK</b></td>
                    </tr>
                    <tr>
                        <td>Reference:</td>
                        <td><b>AZ-{{ $data->ticket_code }}</b></td>
                    </tr>
                </table>
            </fieldset>
            <br/>
            <fieldset class="my-fieldset">
                <legend>Additional info for international players:</legend>
                <table>
                    <tr>
                        <td>Bank name:</td>
                        <td><b>SEB</b></td>
                    </tr>
                    <tr>
                        <td>Recipient:</td>
                        <td><b>5699 3477637</b></td>
                    </tr>
                    <tr>
                        <td>IBAN:</td>
                        <td><b>SE9550000000056993477637</b></td>
                    </tr>
                    <tr>
                        <td>SWIFT:</td>
                        <td><b>ESSESESS</b></td>
                    </tr>
                </table>
            </fieldset>
        </div>

        @else
        You can print your ticket and you're done.
        @endif
    </p>
    <img id="ticket" src="{{ URL::route('game-ticket', array($data->game_id)) }}" alt="Your ticket"/>
    <br/><br/>
    <button id="btn-print-ticket" class="my-btn">Print ticket</button>
</div>
<script>
$('#btn-print-ticket').click(function()
{
    var w = window.open('', 'Ticket printer', 'height=250,width=550')
    w.document.write('<html><head><title>Ticket printer</title></head><body><img id="ticket" src="{{ URL::route('game-ticket', array($data->game_id)) }}"/></body></html>')
    w.print()
})
</script>
@stop