@extends('layout')

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
            <td>{{ @$ticket_template->price_readable }} SEK</td>
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
    <div class="area-btn-paymill">
        <form action="{{ URL::route('booking-done') }}" method="post">
            <input type="hidden" name="is-cash" value="0"/>
            <script
                src="https://button.paymill.com/v1/"
                id="btn-paymill"
                data-label="Pay with credit card"
                data-title="Ticket for"
                data-description="«{{ @$game->name }}»"
                data-submit-button="Pay {{ @$ticket_template->price_readable }} SEK"
                data-amount="{{ @$ticket_template->price }}"
                data-currency="SEK"
                data-public-key="{{ Config::get('app.paymill.public_key') }}"
                data-lang="en-GB"
                >
            </script>
        </form>
    </div>
    @endif
</div>
@stop