@extends('layout')

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="padded-content">
    <div id="calendar">
        <div id="calendar-bar">
            @include('partial/region-picker', ['placement' => 'horizontal', 'defaults' => [1,0], 'prefix' => 'games_'])
            <div style="float:right">
                <a href="{{ URL::route('game-edit') }}">Create your own!</a>
            </div>
        </div>
        <table>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Region</th>
                <th>Booking</th>
            </tr>
            @foreach ($games as $game)
            <tr>
                <td>{{ date('Y.m.d', strtotime($game->starts_at)); }}</td>
                <td>{{ $game->name; }}</td>
                <td>{{ $game->country_name }}, {{ $game->region_name; }}</td>
                <td>
                    @if ($game->editable)
                    <a href="{{ URL::route('game-edit', $game->id) }}">edit</a>
                    @elseif ($game->bookable)
                    <a href="{{ URL::route('game-book', $game->id) }}">book</a>
                    @else
                    no tickets
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@stop