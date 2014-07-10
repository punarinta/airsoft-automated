@extends('layout')

@section('content')
<div class="dialog-box-1">
    <p>Ticket issued. Thanks for your participation.</p>
    <p>
        You have chosen to pay via bank transfer.
        So do it in time, otherwise you will not be able to check-in on the game.
    </p>
    <div style="padding-bottom:30px">
        <fieldset class="my-fieldset">
            <legend>Payment information:</legend>
            <table>
                <tr>
                    <td>Account:</td>
                    <td><b>5699 3477637</b></td>
                </tr>
                <tr>
                    <td>Amount:</td>
                    <td><b>{{ $price }} SEK</b></td>
                </tr>
                <tr>
                    <td>Reference:</td>
                    <td><b>AZ-{{ $ticket_code }}</b></td>
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
    <p>
        You may proceed to <a href="{{ URL::route('game-briefing', $game_id) }}">game briefing</a>
        to get your game party specific information and print your ticket.
    </p>
</div>
@stop