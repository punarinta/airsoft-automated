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

    <div class="area-btn-paymill hidden">
        <form action="{{ URL::route('booking-done', array($game->id)) }}" method="post">
            <input type="hidden" name="game-party-id" class="game-party-id" value="0"/>
            <input type="hidden" name="ticket-template-id" class="ticket-template-id" value="0"/>
            <script
                src="https://button.paymill.com/v1/"
                id="btn-paymill"
                data-label="Pay with credit card"
                data-title="Ticket for"
                data-description="«{{ @$game->name }}»"
                data-submit-button="Pay 2.50 SEK"
                data-amount="250"
                data-currency="SEK"
                data-public-key="{{ Config::get('app.paymill.public_key') }}"
                data-lang="en-GB"
                >
            </script>
        </form>
    </div>

    <div class="area-btn-cash hidden">
        <br/>
        <form action="{{ URL::route('booking-done', array($game->id)) }}" method="post">
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

    $('#btn-paymill').attr('data-amount', data.price)
    $('#btn-paymill').attr('data-submit-button', 'test')

    if (data.is_cash)
    {
        $('.area-btn-paymill').hide()
        $('.area-btn-cash').show()
    }
    else
    {
        $('.area-btn-cash').hide()
        $('.area-btn-paymill').show()
    }

    $('iframe + script, iframe, #btn-paymill', '.area-btn-paymill').remove()
    delete paymill;
  //  $('#btn-paymill').removeAttr('src')
  //  $('#btn-paymill').attr('src', 'https://button.paymill.com/v1/')

    var script = document.createElement('script')
    script.src = 'https://button.paymill.com/v1/'
    script.id="btn-paymill"
    $('.area-btn-paymill form')[0].appendChild(script);
}

$('#ticket-template-id').change(function()
{
    az.ajaxGet('ticket-template', $(this).val(), fillForms)
})
</script>
@stop