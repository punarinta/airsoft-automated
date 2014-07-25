@extends('layout')

@section('title')
| {{ trans('airsoft.briefing.title') }}
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
<style>
#ticket {border:1px solid #ddd;border-radius:3px}
#bank-data {padding-bottom:30px}
</style>
@stop

@section('content')

<div class="window-box-1">
    <p>
        @if (!($data->status & Ticket::STATUS_PAID))
        {{ trans('airsoft.briefing.not-paid') }}
        <div id="bank-data">
            <fieldset class="my-fieldset">
                <legend>{{ trans('airsoft.briefing.head-payment') }}:</legend>
                <table>
                    <tr>
                        <td>{{ trans('airsoft.briefing.head-account') }}:</td>
                        <td><b>5699 3477637</b></td>
                    </tr>
                    <tr>
                        <td>{{ trans('airsoft.briefing.head-amount') }}:</td>
                        <td><b>{{ number_format($data->price / 100, 2) }} SEK</b>
                            @if ($data->factor != 1)
                            ({{ sprintf(Lang::choice('airsoft.briefing.person-count', $data->factor), $data->factor) }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ trans('airsoft.briefing.head-reference') }}:</td>
                        <td><b>AZ-{{ $data->ticket_code }}</b></td>
                    </tr>
                </table>
            </fieldset>
            <br/>
            <fieldset class="my-fieldset">
                <legend>{{ trans('airsoft.briefing.head-payment-int') }}:</legend>
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
        {{ trans('airsoft.briefing.print-ticket') }}
        @endif
    </p>
    <img id="ticket" src="{{ URL::route('game-ticket', array($data->game_id)) }}" alt="Your ticket"/>
    <br/><br/>
    <button id="btn-print-ticket" class="my-btn">{{ trans('airsoft.briefing.do-print-ticket') }}</button>

    <hr class="my-hr"/>
    @if ($map)
    <iframe class="map-frame" src="{{ $map }}" width="100%" height="480"></iframe>
    @else
    {{ trans('airsoft.briefing.no-map') }}
    @endif
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