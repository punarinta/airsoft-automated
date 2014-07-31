@extends('layout')

@section('title')
| Game editor
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
{{ HTML::style('/css/pikaday.css') }}
{{ HTML::script('/js/moment.min.js') }}
{{ HTML::script('/js/pikaday.min.js') }}
@stop

@section('content')
<?php
$mapType = $game->getSetting('map.type');
?>
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
                <td>
                    <input type="text" class="my-input w100 starts-at" value="{{ date('Y-m-d', strtotime($game->starts_at)) }}" placeholder="YYYY-MM-DD"/>
                    <input type="text" class="my-input w100 starts-at-time" value="{{ date('H:i', strtotime($game->starts_at)) }}" placeholder="HH:MM"/>
                </td>
            </tr>
            <tr>
                <td>Ends&nbsp;at:</td>
                <td>
                    <input type="text" class="my-input w100 ends-at" value="{{ date('Y-m-d', strtotime($game->ends_at)) }}" placeholder="YYYY-MM-DD"/>
                    <input type="text" class="my-input w100 ends-at-time" value="{{ date('H:i', strtotime($game->ends_at)) }}" placeholder="HH:MM"/>
                </td>
            </tr>
            <tr>
                <td>Bookable:</td>
                <td><input class="is-visible" type="checkbox" {{ $game->is_visible?'checked':'' }}/></td>
            </tr>
            <tr>
                <td>Game site:</td>
                <td><input type="text" class="my-input url" value="{{ $game->getSetting('url') }}" placeholder="http://"/></td>
            </tr>
            <tr>
                <td>Banner:</td>
                <td>
                    <input type="text" class="my-input poster" value="{{ $game->getSetting('poster') }}" placeholder="http://"/>
                    &nbsp;<span class="poster-url-tester"></span>
                </td>
            </tr>
            <tr>
                <td>Extra:</td>
                <td>
                    <label class="my-check"><input type="checkbox" class="req-nick" {{ $game->getSetting('req.nick')?'checked="checked"':'' }}/> require nickname for booking</label><br/>
                    <label class="my-check"><input type="checkbox" class="req-phone" {{ $game->getSetting('req.phone')?'checked="checked"':'' }}/> require phone for booking</label><br/>
                    <label class="my-check"><input type="checkbox" class="req-age" {{ $game->getSetting('req.age')?'checked="checked"':'' }}/> require age for booking</label><br/>
                </td>
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
                <td><input type="number" min="1" class="my-input w100 players-limit" value="{{ isset($game->parties[0])?$game->parties[0]->players_limit:0 }}"/></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea class="my-text description">{{ isset($game->parties[0])?$game->parties[0]->description:'' }}</textarea></td>
            </tr>
        </table>

        @if (isset($game->parties[0]))
        <button class="my-btn save">Save</button>
        <button class="my-btn delete">Delete</button>
        @else
        <button class="my-btn save hidden">Save</button>
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
                <td><input type="number" min="0" class="my-input w100 price" value="{{ isset($game->ticket_templates[0]) ? $game->ticket_templates[0]->price:0 }}"/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;from:</td>
                <td><input type="text" class="my-input price-date-start" value="{{ date('Y-m-d', strtotime(isset($game->ticket_templates[0]) ? $game->ticket_templates[0]->price_date_start:'today')) }}"/></td>
            </tr>
            <tr>
                <td>Valid&nbsp;to:</td>
                <td><input type="text" class="my-input price-date-end" value="{{ date('Y-m-d', strtotime(isset($game->ticket_templates[0]) ? $game->ticket_templates[0]->price_date_end:'+30 days')) }}"/></td>
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
        <button class="my-btn save hidden">Save</button>
        <button class="my-btn delete hidden">Delete</button>
        @endif
        <button class="my-btn add">Add new</button>
    </fieldset>
    <br/>

    <fieldset class="my-fieldset" id="form-map"
    @if (!$game->id)
    disabled
    @endif
    >
        <legend>Game map</legend>
        <table>
            <tr>
                <td>Map type:</td>
                <td>
                    <select class="my-select map-type-id">
                        <option value="1" {{ $mapType == 1 ? 'selected="selected"' : '' }}>Embedded GMap</option>
                        <option value="2" {{ $mapType == 2 ? 'selected="selected"' : '' }}>Image (URL)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Source:</td>
                <td>
                    @if ($mapType <= 1)
                    <input type="text" class="my-input w200 map-source" value="{{ $game->getSetting('map.source') }}"/>
                    <a class="map-edit-link" target="_blank" href="https://mapsengine.google.com/map/u/0/{{ strlen($game->getSetting('map.source')) ? 'edit?mid=' . $game->getSetting('map.source') : '' }}">edit</a>
                    @elseif ($mapType == 2)
                    <input type="text" class="my-input map-source" value="{{ $game->getSetting('map.source') }}"/>
                    @endif
                </td>
            </tr>
        </table>

        <button class="my-btn save">Save</button>
        <br/><br/>

        @if ($mapType <= 1)
        <iframe class="map-frame
        @if (!strlen(trim($game->getSetting('map.source'))))
        hidden
        @endif
        >" src="https://mapsengine.google.com/map/embed?mid={{ $game->getSetting('map.source') }}" width="100%" height="480"></iframe>
        @elseif ($mapType == 2)
        <img src="{{ $game->getSetting('map.source') }}" alt="Game map" width="100%" class="map-frame
        @if (!strlen(trim($game->getSetting('map.source'))))
        hidden
        @endif
        >"/>
        @endif
    </fieldset>

</div>
<script>
    var gameId = {{ $game->id ? $game->id : 0 }};

    /*
     Add buttons
     */

    $('#form-game-party .add').click(function()
    {
        var data = JSON.stringify(
        {
            name: $('#form-game-party .name').val(),
            game_id: gameId,
            players_limit: $('#form-game-party .players-limit').val()
        })

        az.ajaxPost('game-party', data, function(data)
        {
            $('.delete, .save', '#form-game-party').show()
            $('#form-game-party .game-party-id').append('<option value="' + data.id + '">' + data.name + '</option>')
            $('#form-ticket-template .game-party-id').append('<option value="' + data.id + '">' + data.name + '</option>')
        })
    })

    $('#form-ticket-template .add').click(function()
    {
        var data = JSON.stringify(
        {
            game_id: gameId,
            game_party_id: $('#form-ticket-template .game-party-id').val(),
            price: $('#form-ticket-template .price').val(),
            price_date_start: $('#form-ticket-template .price-date-start').val(),
            price_date_end: $('#form-ticket-template .price-date-end').val(),
            is_visible: $('#form-ticket-template .is-cash').is(':checked')
        })

        az.ajaxPost('ticket-template', data, function(data)
        {
            if (!data.name)
            {
                data.name = 'temp-' + data.id
            }

            $('.delete, .save', '#form-ticket-template').show()
            $('#form-ticket-template .ticket-template-id').append('<option value="' + data.id + '">' + data.name + '</option>')
        })
    })

    /*
     Save buttons
     */

    $('#form-game .save').click(function()
    {
        var data = JSON.stringify(
        {
            name: $('#form-game .name').val(),
            region_id: game_region_picker.getLocation()[1],
            starts_at: $('#form-game .starts-at').val() + ' ' + $('#form-game .starts-at-time').val(),
            ends_at: $('#form-game .ends-at').val() + ' ' + $('#form-game .ends-at-time').val(),
            is_visible: $('#form-game .is-visible').is(':checked') * 1,
            url: $('#form-game .url').val(),
            poster: $('#form-game .poster').val(),
            req_nick: $('#form-game .req-nick').is(':checked') * 1,
            req_phone: $('#form-game .req-phone').is(':checked') * 1,
            req_age: $('#form-game .req-age').is(':checked') * 1
        })

        if (!gameId) az.ajaxPost('game', data, function(data)
        {
            gameId = data.id
            $('#form-game .delete').show()
            $('#form-game .save').text('Save')
            $('.my-fieldset').removeAttr('disabled')
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
            players_limit: $('#form-game-party .players-limit').val(),
            description: $('#form-game-party .description').val()
        })

        az.ajaxPut('game-party', gamePartyId, data)
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

        az.ajaxPut('ticket-template', ticketTemplateId, data)
    })

    $('#form-map .save').click(function()
    {
        var map_type_id = $('#form-map .map-type-id').val()

        var data = JSON.stringify(
        {
            game_id: gameId,
            cmd_save_map: 1,
            map_type_id: map_type_id,
            map_source: $('#form-map .map-source').val()
        })

        az.modalCallback = function(json)
        {
            var s = JSON.parse(json.data.settings)
            $('#form-map .map-source').val(s.map.source)
            if (s.map.source.length && map_type_id == 1)
            {
                $('#form-map .map-frame').show().attr('src','https://mapsengine.google.com/map/embed?mid=' + s.map.source)
                $('#form-map .map-edit-link').attr('href', 'https://mapsengine.google.com/map/u/0/edit?mid=' + s.map.source)
            }
            else
            {
                $('#form-map .map-edit-link').attr('href', 'https://mapsengine.google.com/map/u/0/')
                $('#form-map .map-frame').hide()
            }
        }

        az.ajaxPut('game', gameId, data)
    })

    /*
        Delete buttons
    */

    var confText = 'Are you sure you want to completely remove '

    $('#form-game .delete').click(function()
    {
        if (confirm(confText + 'game «{{ $game->name }}»?'))
        {
            az.modalCallback = function()
            {
                document.location.href = '/games'
            }
            az.ajaxDelete('game', gameId)
        }
    })

    $('#form-game-party .delete').click(function()
    {
        if (confirm(confText + 'game party «' + $('#form-game-party .game-party-id option:selected').text() + '»?'))
        {
            var val = $('#form-game-party .game-party-id').val()
            az.ajaxDelete('game-party', val)

            // remove this game-party from ticket-template's GP list
            $('#form-ticket-template .game-party-id option[value="' + val + '"]').remove()

            // remove it from the game parties list also and resync
            $('#form-game-party .game-party-id option[value="' + val + '"]').remove()
            $('#form-game-party .game-party-id').change()
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

    $('#form-game .poster').on('change focusout',function()
    {
        $('#form-game .poster-url-tester').text('testing...')

        var i = new Image
        i.onload = function()
        {
            if ('naturalHeight' in this)
            {
                if (this.naturalHeight + this.naturalWidth === 0)
                {
                    $('#form-game .poster-url-tester').html('<b>not an image!</b>')
                }
            }
            else if (this.width + this.height == 0)
            {
                $('#form-game .poster-url-tester').html('<b>Not an image!</b>')
            }
            $('#form-game .poster-url-tester').html('<b>OK</b>')
        }
        i.onerror = function()
        {
            $('#form-game .poster-url-tester').html('<b>not an image!</b>')
        }
        i.src = $('#form-game .poster').val()
    })

    $('#form-map .map-type-id').change(function()
    {
        switch ($(this).val() - 0)
        {
            case 1:
                $('#form-map .map-edit-link').show()
                break

            case 2:
                $('#form-map .map-edit-link').hide()
        }
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