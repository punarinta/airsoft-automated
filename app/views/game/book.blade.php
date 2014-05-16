@extends('layout')

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="dialog-box-1">
    @if ($is_organizer)
    <p>NB: you are organizing this game</p>
    @endif

    <fieldset class="my-fieldset" id="form-ticket">
        <legend>Choose a ticket</legend>
        <table>
            <tr>
                <td>Game party:</td>
                <td>
                    <select class="my-select game-party-id">
                        @foreach ($game->parties as $party)
                        <option value="{{ $party->id }}">{{ $party->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Available tickets:</td>
                <td>
                    <select class="my-select ticket-template-id">
                        @foreach ($game->ticket_templates as $ticket_template)
                        <option value="{{ $ticket_template->id }}">{{ $ticket_template->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>

    <div class="area-btn-paymill">
        <form action="{{ URL::route('booking-done') }}" method="post">
            <script
                src="https://button.paymill.com/v1/"
                id="btn-paymill"
                data-label="Pay with credit card"
                data-title="Ticket for"
                data-description="«{{ @$game->name }}»"
                data-submit-button="Pay 2.50 SEK"
                data-amount="250"
                data-currency="SEK"
                data-public-key="795333315179a2f8da89a287920eb299"
                data-lang="en-GB"
                >
            </script>
        </form>
    </div>

    <div class="area-btn-cash">
        <button class="my-btn">Confirm</button>
    </div>
</div>
<script>

</script>
@stop