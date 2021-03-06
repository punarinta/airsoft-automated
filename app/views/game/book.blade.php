@extends('layout')

@section('title')
| {{ trans('airsoft.booking.title') }}
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="dialog-box-1">
    <p>{{ sprintf(trans('airsoft.booking.game-info'), $game->name, $game->organizer) }}</p>

    @if ($is_organizer)
    <p>{{ trans('airsoft.booking.you-organize') }}</p>
    @endif

    @if (!empty ($requirements))
    <fieldset class="my-fieldset">
        <legend>{{ trans('airsoft.booking.head-requirements') }}</legend>
        <table>
            @foreach ($requirements as $requirement)
            <tr>
                <td>{{ $requirement[0] }}:</td>
                <td>{{ $requirement[1] ? 'OK' : '<span class="warn-span">' . trans('airsoft.booking.do-fill-profile') . '</span>' }}</td>
            </tr>
            @endforeach
        </table>
    </fieldset>
    <br/>
    @endif

    <fieldset class="my-fieldset" id="form-ticket"
        @if (!$requirements_ok)
        disabled="disabled"
        @endif
        >
        <legend>{{ trans('airsoft.booking.head-ticket') }}</legend>
        <table>
            <tr>
                <td>{{ trans('airsoft.booking.head-party') }}:</td>
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
                <td>{{ trans('airsoft.booking.head-available-tickets') }}:</td>
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
    <br/>

    <fieldset class="my-fieldset" id="form-options">
        <legend>{{ trans('airsoft.booking.head-options') }}</legend>
        <div>
            {{ trans('airsoft.booking.head-persons-count') }}: &nbsp;
            <input type="number" value="1" class="my-input factor w30" min="1"/>
        </div>
    </fieldset>

    <div id="area-confirm" class="hidden">
        <br/>
        <form action="{{ URL::route('pay-booked') }}" method="post">
            <input type="hidden" name="factor" class="factor" value="1"/>
            <input type="hidden" name="game-id" class="game-id" value="{{ $game->id }}"/>
            <input type="hidden" name="game-party-id" class="game-party-id" value="0"/>
            <input type="hidden" name="ticket-template-id" class="ticket-template-id" value="0"/>
            <input type="submit" class="confirm my-btn" value="Confirm"/>
        </form>
    </div>
</div>
<script>
function fillForms(data)
{
    if (data.game_party_id-0)
    {
        // just for convenience
        $('#game-party-id').val(data.game_party_id)
    }

    $('.game-party-id').val($('#game-party-id').val())
    $('.ticket-template-id').val($('#ticket-template-id').val())
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

$('#area-confirm .confirm').click(function()
{
    var p = $('.game-party-id').val() - 0
    if (!p)
    {
        az.showModal('{{ trans('airsoft.booking.pick-both') }}')
        return false
    }
    $('#area-confirm .factor').val($('#form-options .factor').val())
    return true
})
</script>
@stop