@extends('layout')

@section('title')
| «{{ $game->name }}»
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
<style>
#poster {margin-bottom:20px;width:100%;height:307px;border-radius:3px;background-image:url('{{ $game->poster }}');background-size:cover;background-position:center}
#poster .caption {display:inline-block;padding:15px 25px;border-radius:10px;position:relative;top:205px;left:25px;background:rgba(20,20,20,.6)}
#poster .caption h1 {color:#fff;margin:0;font-size:36px}
#poster .dates {float:right;display:inline-block;padding:15px 25px;border-radius:10px;position:relative;top:25px;right:25px;background:rgba(20,20,20,.6)}
#poster .dates h2 {color:#fff;margin:0;font-size:24px}
</style>
@stop

@section('content')
<div class="window-box-1">
    <div id="poster">
        <div class="caption">
            <h1>{{ $game->name }}</h1>
        </div>
        <div class="dates">
            <h2>{{ date('d.m', strtotime($game->starts_at)) }} &mdash; {{ date('d.m', strtotime($game->ends_at)) }}</h2>
        </div>
    </div>

    <table style="width:100%">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>Game starts:</td>
                        <td>{{ date('d.m.Y \@ H:i', strtotime($game->starts_at)) }}</td>
                    </tr>
                    <tr>
                        <td>Game ends:</td>
                        <td>{{ date('d.m.Y \@ H:i', strtotime($game->ends_at)) }}</td>
                    </tr>
                    <tr>
                        <td>Region:</td>
                        <td>{{ @$geo->region_name ?: '&ndash;' }}, {{ @$geo->country_name ?: '&ndash;' }}</td>
                    </tr>
                    <tr>
                        <td>External info:</td>
                        <td>
                            @if (strlen($game->getSetting('url')))
                            <a target="_blank" rel="nofollow" href="{{ $game->getSetting('url') }}">{{ $game->getSetting('url') }}</a>
                            @else
                            not present
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top">Fractions:</td>
                        <td>
                            @foreach ($parties as $party)
                            {{ $party->getName() }} (max. {{ $party->getPlayersLimit() }} players)
                            <br/>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                @if ($bookable)
                <a href="{{ URL::route('game-book', $game->id) }}" title="Book tickets and pay"><img src="/gfx/ticket-icon.png" alt="Order tickets"/></a>
                @else
                @endif
            </td>
        </tr>
    </table>

    <br/>

    @if ($game->map)
    <iframe class="map-frame" src="{{ $game->map }}" width="100%" height="550"></iframe>
    @else
    No map yet present for this game.
    @endif
</div>
@stop