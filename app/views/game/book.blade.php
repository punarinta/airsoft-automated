@extends('layout')

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="dialog-box-1">
    @if ($is_organizer)
    <p>Note: you are organizing this game</p>
    @endif

    <fieldset class="my-fieldset" id="form-ticket">
        <legend>Choose a ticket</legend>
        <table>
            <tr>
                <td>Game party:</td>
                <td>
                    <select class="my-select" id="game-party-id">
                        <option value="0">Not specified</option>
                        @foreach ($game->parties as $party)
                        <option value="{{ $party->id }}">{{ $party->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Available tickets:</td>
                <td>
                    <select class="my-select" id="ticket-template-id">
                        <option value="0">Not specified</option>
                        @foreach ($game->ticket_templates as $ticket_template)
                        <option value="{{ $ticket_template->id }}">{{ $ticket_template->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>

    <div id="area-confirm" class="hidden">
        <br/>
        <form action="{{ URL::route('pay-booked', array($game->id)) }}" method="post">
            <input type="hidden" name="game-id" class="game-id" value="{{ $game->id }}"/>
            <input type="hidden" name="game-party-id" class="game-party-id" value="0"/>
            <input type="hidden" name="ticket-template-id" class="ticket-template-id" value="0"/>
            <input type="submit" class="my-btn" value="Confirm"/>
        </form>
    </div>
</div>
<script>
function fillForms(data)
{
    $('.game-party-id').val($('#game-party-id').val())
    $('.ticket-template-id').val($('#ticket-template-id').val())

    if (data.game_party_id)
    {
        // just for convenience
        $('#game-party-id').val(data.game_party_id)
    }
}

$('#ticket-template-id').change(function()
{
    if ($(this).val()-0)
    {
        $('#area-confirm').show()
        az.ajaxGet('ticket-template', $(this).val(), fillForms)
    }
    else $('#area-confirm').hide()
})

$('#game-party-id').change(function()
{
    $('#ticket-template-id').val(0)
})
</script>
@stop