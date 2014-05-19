@extends('layout')

@section('header')
{{ HTML::style('/css/games.css') }}
{{ HTML::style('/css/pikaday.css') }}
{{ HTML::script('/js/moment.min.js') }}
{{ HTML::script('/js/pikaday.js') }}
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
                <td><input type="text" class="my-input starts-at" value="{{ date('Y-m-d', strtotime($game->starts_at)) }}"/></td>
            </tr>
            <tr>
                <td>Ends&nbsp;at:</td>
                <td><input type="text" class="my-input ends-at" value="{{ date('Y-m-d', strtotime($game->ends_at)) }}"/></td>
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
        <select class="my-select game-party-id">
            @foreach ($game->parties as $party)
            <option value="{{ $party->id }}">{{ $party->name }}</option>
            @endforeach
        </select>
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" class="my-input name" value="{{ isset($game->parties[0])?$game->parties[0]->name:'' }}"/></td>
            </tr>
            <tr>
                <td>Players&nbsp;limit:</td>
                <td><input type="text" class="my-input players-limit" value="{{ isset($game->parties[0])?$game->parties[0]->players_limit:0 }}"/></td>
            </tr>
        </table>

        @if (isset($game->parties[0]))
        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
        @else
        <button class="my-btn save">Create</button>
        <button class="my-btn delete hidden">Delete</button>
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
                    <select class="my-select game-party-id">
                        <option value="0">All / Any</option>
                        @foreach ($game->parties as $party)
                        <option value="{{ $party->id }}">{{ $party->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="text" class="my-input price" value="{{ isset($game->ticket_templates[0]) ? $game->ticket_templates[0]->price:0 }}"/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;from:</td>
                <td><input type="text" class="my-input price-date-start" value="{{ isset($game->ticket_templates[0]) ? date('Y-m-d', strtotime($game->ticket_templates[0]->price_date_start)):0 }}"/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;to:</td>
                <td><input type="text" class="my-input price-date-end" value="{{ isset($game->ticket_templates[0]) ? date('Y-m-d', strtotime($game->ticket_templates[0]->price_date_end)):0 }}"/></td>
            </tr>
            <tr>
                <td>Cash&nbsp;only:</td>
                <td><input type="checkbox" class="is-cash" {{ isset($game->ticket_templates[0]) && $game->ticket_templates[0]->is_cash ? 'checked' : '' }}/></td>
            </tr>
        </table>

        @if (isset($game->ticket_templates[0]))
        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
        @else
        <button class="my-btn save">Create</button>
        <button class="my-btn delete hidden">Delete</button>
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
        var gamePartyId = $('#form-game-party .game-party-id').val() - 0

        var data = JSON.stringify(
        {
            name: $('#form-game-party .name').val(),
            game_id: gameId,
            players_limit: $('#form-game-party .players-limit').val()
        })

        if (!gamePartyId) az.ajaxPost('game-party', data, function(data)
        {
            $('#form-game-party .delete').show()
            $('#form-game-party .save').text('Save')
            $('#form-game-party .game-party-id').append('<option value="' + data.id + '">' + data.name + '</option>')
            $('#form-ticket-template .game-party-id').append('<option value="' + data.id + '">' + data.name + '</option>')
        })
        else
        {
            az.ajaxPut('game', gamePartyId, data)
        }
    })

    $('#form-ticket-template .save').click(function()
    {
        var ticketTemplateId = $('#form-ticket-template .ticket-template-id').val() - 0

        var data = JSON.stringify(
        {
            game_id: gameId,
            game_party_id: $('#form-ticket-template .game-party-id').val(),
            price: $('#form-ticket-template .price').val(),
            price_date_start: $('#form-ticket-template .price-date-start').val(),
            price_date_end: $('#form-ticket-template .price-date-end').val(),
            is_visible: $('#form-ticket-template .is-cash').is(':checked')
        })

        if (!ticketTemplateId) az.ajaxPost('ticket-template', data, function(data)
        {
            $('#form-ticket-template .delete').show()
            $('#form-ticket-template .save').text('Save')
            $('#form-ticket-template .ticket-template-id').append('<option value="' + data.id + '">' + data.name + '</option>')
        })
        else
        {
            az.ajaxPut('ticket-template', ticketTemplateId, data)
        }
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
            var val = $('#form-game-party .party-id').val()
            az.ajaxDelete('game-party', val)

            // remove this game-party from ticket-template's GP list
            $('#form-ticket-template .game-party-id option[value="' + val + '"]').remove()
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

    $('#form-game-party .game-party-id').change(function()
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
            $('#form-ticket-template .price').val(Math.round(data.price / 100))
            $('#form-ticket-template .price-date-start').val(data.price_date_start)
            $('#form-ticket-template .price-date-end').val(data.price_date_end)
            $('#form-ticket-template .is-cash').val(data.is_cash)
        })
    })

    // datepickers
    new Pikaday(
    {
        field: $('#form-game .starts-at')[0],
        firstDay: 1,
        format: 'YYYY-MM-DD'
    })
    new Pikaday(
    {
        field: $('#form-game .ends-at')[0],
        firstDay: 1,
        format: 'YYYY-MM-DD'
    })
    new Pikaday(
    {
        field: $('#form-ticket-template .price-date-start')[0],
        firstDay: 1,
        format: 'YYYY-MM-DD'
    })
    new Pikaday(
    {
        field: $('#form-ticket-template .price-date-end')[0],
        firstDay: 1,
        format: 'YYYY-MM-DD'
    })
</script>

@stop