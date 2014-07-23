@extends('layout')

@section('title')
| Pay
@stop

@section('content')
<div class="dialog-box-1">
    <table>
        <tr>
            <td>Game name:</td>
            <td>{{ @$game->name }}</td>
        </tr>
        <tr>
            <td>Game party:</td>
            <td>{{ @$game_party->name }}</td>
        </tr>
        <tr>
            <td>Price:</td>
            <td>{{ @$price_factor }} x {{ @$ticket_template->price_readable }} SEK &nbsp;=&nbsp; {{ @$price_readable_total }} SEK</td>
        </tr>
    </table>
    <br/>
    @if ($ticket_template->is_cash)
    <p>
        This ticket has payment by cash only.
        You will have to bring <b>{{ @$ticket_template->price_readable }} SEK</b> to the game check-in area before the game.
    </p>
    <form action="{{ URL::route('booking-done') }}" method="post">
        <input type="hidden" name="is-cash" value="1"/>
        <input type="submit" class="my-btn" value="Confirm"/>
    </form>
    @else
    <div>
        Available payment options:
        <table class="my-table table-pp-list">
            <tr>
                <td><a href="{{ URL::route('bank-booking-done') }}"><img src="/gfx/pp-1.png" alt="Bank transfer"/></a></td>
                <td><a class="my-link" href="{{ URL::route('bank-booking-done') }}">Bank transfer (0% charge)</a></td>
            </tr>
        </table>
    </div>
<!--    <div class="area-btn-paymill">
        <form action="{{ URL::route('booking-done') }}" method="post">
            <input type="hidden" name="is-cash" value="0"/>
            <script
                src="https://button.paymill.com/v1/"
                id="btn-paymill"
                data-label="Pay with credit card"
                data-title="Ticket for"
                data-description="«{{ @$game->name }}»"
                data-submit-button="Pay {{ @$price_readable_total }} SEK"
                data-amount="{{ @$ticket_template->price }}"
                data-currency="SEK"
                data-public-key="{{ Config::get('app.paymill.public_key') }}"
                data-lang="en-GB"
                >
            </script>
        </form>
    </div>-->
    @endif
</div>
<style>
    .table-pp-list {width:100%}
    .table-pp-list td {padding-top:8px}
    .table-pp-list a {color:#15c;text-decoration:none}
</style>
@stop