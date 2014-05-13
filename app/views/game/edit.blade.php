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
                <td><input type="text" class="my-input name" value="{{ $game->id }}"/></td>
            </tr>
            <tr>
                <td>Region:</td>
                <td>@include('partial/region-picker', ['placement' => 'horizontal', 'defaults' => [$game->country_id, $game->region_id], 'prefix' => 'game_'])</td>
            </tr>
            <tr>
                <td>Starts&nbsp;at:</td>
                <td><input class="my-input starts-at" value="{{ $game->starts_at }}"/></td>
            </tr>
            <tr>
                <td>Ends&nbsp;at:</td>
                <td><input class="my-input ends-at" value="{{ $game->ends_at }}"/></td>
            </tr>
            <tr>
                <td>Bookable:</td>
                <td><input class="is-visible" type="checkbox" {{ $game->is_visible?'checked':'' }}/></td>
            </tr>
        </table>
        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
    </fieldset>
    <br/>

    <fieldset class="my-fieldset" id="form-game-party">
        <legend>Game parties</legend>
        <select id="sel-party-id" class="my-select">
            @foreach ($game->parties as $party)
            <option value="{{ $party->id }}">{{ $party->name }}</option>
            @endforeach
        </select>
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" class="my-input name"/></td>
            </tr>
            <tr>
                <td>Players&nbsp;limit:</td>
                <td><input type="text" class="my-input players-limit"/></td>
            </tr>
        </table>

        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
        <button class="my-btn add">Add new</button>
    </fieldset>
    <br/>

    <fieldset class="my-fieldset" id="form-ticket-template">
        <legend>Ticket types:</legend>
        <select id="sel-ticket-id" class="my-select">
            @foreach ($game->ticket_templates as $ticket_template)
            <option value="{{ $ticket_template->id }}">{{ $ticket_template->name }}</option>
            @endforeach
        </select>
        <table>
            <tr>
                <td>Game&nbsp;party:</td>
                <td><input type="text" class="my-input game-party-id" value=""/></td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="text" class="my-input price" value=""/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;from:</td>
                <td><input class="date my-date price-date-start" value=""/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;to:</td>
                <td><input class="date my-date price-date-end" value=""/></td>
            </tr>
            <tr>
                <td>Cash&nbsp;only:</td>
                <td><input type="checkbox" {{ $game->is_cash?'checked':'' }}/></td>
            </tr>
        </table>

        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
        <button class="my-btn add">Add new</button>
    </fieldset>

</div>
<script>
    $('#form-game .save').click(function()
    {
        var data = JSON.stringify(
        {
            name: $('#form-game .name').val(),
            region_id: geam_region_picker.getLocation()[1],
            starts_at: $('#form-game .starts-at').val(),
            ends_at: $('#form-game .ends-at').val(),
            is_visible: $('#form-game .is-visible').is(':checked')
        })

        @if (!$game->id)
        $.ajax(
        {
            url: '/api/game',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(json)
            {
                alert(json.errMsg ? json.errMsg : 'Saved')
            }
        })

        @else

        $.ajax(
        {
            url: '/api/game/{{ $game->id }}',
            type: 'PUT',
            dataType: 'json',
            data: data,
            success: function(json)
            {
                alert(json.errMsg ? json.errMsg : 'Saved')
            }
        })
        @endif
    })
</script>

@stop