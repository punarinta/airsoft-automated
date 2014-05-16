@extends('layout')

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="window-box-1">
    @if ($is_organizer)
    <p>NB: you are organizing this game</p>
    @endif

    <fieldset class="my-fieldset">
        <legend>Pick a ticket</legend>
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
</div>
<script>

</script>
@stop