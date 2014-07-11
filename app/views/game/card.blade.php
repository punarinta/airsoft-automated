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
            <td>{{ $geo->region_name }}, {{ $geo->country_name }}</td>
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
    </table>
    <br/>

    @if ($game->map)
    <iframe class="map-frame" src="{{ $game->map }}" width="100%" height="550"></iframe>
    @else
    No map yet present for this game.
    @endif
</div>
@stop