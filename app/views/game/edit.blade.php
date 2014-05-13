@extends('layout')

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')

<div class="window-box-1">
    <fieldset class="my-fieldset" id="form-game">
        <legend>General</legend>
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" class="my-input name" value="{{ $game->name }}"/></td>
            </tr>
            <tr>
                <td>Region:</td>
                <td>@include('partial/region-picker', ['placement' => 'horizontal', 'defaults' => [$game->country_id, $game->region_id], 'prefix' => 'game_'])</td>
            </tr>
            <tr>
                <td>Starts&nbsp;at:</td>
                <td><input type="date" class="my-date starts-at" value="{{ date('Y-m-d', strtotime($game->starts_at)) }}"/></td>
            </tr>
            <tr>
                <td>Ends&nbsp;at:</td>
                <td><input type="date" class="my-date ends-at" value="{{ date('Y-m-d', strtotime($game->ends_at)) }}"/></td>
            </tr>
            <tr>
                <td>Bookable:</td>
                <td><input class="is-visible" type="checkbox" {{ $game->is_visible?'checked':'' }}/></td>
            </tr>
        </table>
        @if ($game->id)
        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
        @else
        <button class="my-btn save">Create</button>
        <button class="my-btn delete hidden">Delete</button>
        @endif
        </fieldset>
    <br/>

    <fieldset class="my-fieldset" id="form-game-party"
    @if (!$game->id)
    disabled
    @endif
    >
        <legend>Game parties</legend>
        <select class="my-select party-id">
            @foreach ($game->parties as $party)
            <option value="{{ $party->id }}">{{ $party->name }}</option>
            @endforeach
        </select>
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" class="my-input name" value="{{ $game->parties?$game->parties[0]->name:'' }}"/></td>
            </tr>
            <tr>
                <td>Players&nbsp;limit:</td>
                <td><input type="text" class="my-input players-limit" value="{{ $game->parties?$game->parties[0]->players_limit:0 }}"/></td>
            </tr>
        </table>

        <button class="my-btn save">Save</button>
        @if ($game->parties)
        <button class="my-btn delete">Delete</button>
        @endif
        <button class="my-btn add">Add new</button>
    </fieldset>
    <br/>

    <fieldset class="my-fieldset" id="form-ticket-template"
    @if (!$game->id)
    disabled
    @endif
    >
        <legend>Ticket types:</legend>
        <select class="my-select ticket-template-id">
            @foreach ($game->ticket_templates as $ticket_template)
            <option value="{{ $ticket_template->id }}">{{ $ticket_template->name }}</option>
            @endforeach
        </select>
        <table>
            <tr>
                <td>Game&nbsp;party:</td>
                <td>
                    <input type="text" class="my-input game-party-name" value="{{ $game->ticket_templates?$game->ticket_templates[0]->name:'' }}"/>
                </td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="text" class="my-input price" value="{{ $game->ticket_templates?$game->ticket_templates[0]->price:0 }}"/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;from:</td>
                <td><input type="date" class="my-date price-date-start" value="{{ $game->ticket_templates?date('Y-m-d', strtotime($game->ticket_templates[0]->price_date_start)):0 }}"/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;to:</td>
                <td><input type="date" class="my-date price-date-end" value="{{ $game->ticket_templates?date('Y-m-d', strtotime($game->ticket_templates[0]->price_date_end)):0 }}"/></td>
            </tr>
            <tr>
                <td>Cash&nbsp;only:</td>
                <td><input type="checkbox" class="is-cash" {{ $game->ticket_templates && $game->ticket_templates[0]->is_cash ? 'checked' : '' }}/></td>
            </tr>
        </table>

        <button class="my-btn save">Save</button>
        @if ($game->ticket_templates)
        <button class="my-btn delete">Delete</button>
        @endif
        <button class="my-btn add">Add new</button>
    </fieldset>

</div>
<script>

    var gameId = {{ $game->id ? $game->id : 0 }};

    /*
     Save buttons
     */

    $('#form-game .save').click(function()
    {
        var data = JSON.stringify(
        {
            name: $('#form-game .name').val(),
            region_id: game_region_picker.getLocation()[1],
            starts_at: $('#form-game .starts-at').val(),
            ends_at: $('#form-game .ends-at').val(),
            is_visible: $('#form-game .is-visible').is(':checked')
        })

        if (!gameId) az.ajaxPost('game', data, function(data)
        {
            gameId = data.id
            $('#form-game .delete').show()
            $('#form-game .save').text('Save')
            $('#form-game-party, #form-ticket-template').removeAttr('disabled')
        })
        else
        {
            az.ajaxPut('game', gameId, data)
        }
    })

    $('#form-game-party .save').click(function()
    {

    })

    $('#form-ticket-template .save').click(function()
    {
    })

    /*
        Delete buttons
    */

    var confText = 'Are you sure you want to completely remove '

    $('#form-game .delete').click(function()
    {
        if (confirm(confText + 'game «{{ $game->name }}»?'))
        {
            az.ajaxDelete('game', gameId)
        }
    })

    $('#form-game-party .delete').click(function()
    {
        if (confirm(confText + 'game party «' + $('#form-game-party .party-id option:selected').text() + '»?'))
        {
            az.ajaxDelete('game-party', $('#form-game-party .party-id').val())
        }
    })

    $('#form-ticket-template .delete').click(function()
    {
        if (confirm(confText + 'ticket template «' + $('#form-ticket-template .ticket-template-id option:selected').text() + '»?'))
        {
            az.ajaxDelete('ticket-template', $('#form-ticket-template .ticket-template-id').val())
        }
    })


    /*
        On-changes
     */

    $('#form-game-party .party-id').change(function()
    {
        az.ajaxGet('game-party', $(this).val(), function(data)
        {
            $('#form-game-party .name').val(data.name)
            $('#form-game-party .players-limit').val(data.players_limit)
        })
    })

    $('#form-ticket-template .ticket-template-id').change(function()
    {
        az.ajaxGet('ticket-template', $(this).val(), function(data)
        {
            $('#form-ticket-template .name').val(data.name)
            $('#form-ticket-template .price').val(data.price)
            $('#form-ticket-template .price-date-start').val(data.price_date_start)
            $('#form-ticket-template .price-date-end').val(data.price_date_end)
            $('#form-ticket-template .is-cash').val(data.is_cash)
        })
    })
</script>

@stop