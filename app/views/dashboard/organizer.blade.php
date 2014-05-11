@extends('layout')

@section('header')
{{ HTML::style('/css/dashboard.css') }}
@stop

@section('content')

<div class="window-box-1">
    <div id="calendar">
        <div id="calendar-bar">
            <a class="my-btn" style="float:left" href="{{ URL::route('game-edit') }}">Create new game</a>
        </div>
        <table class="my-table">
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Region</th>
                <th>Booked tickets</th>
                <th>Total earned</th>
                <th>Edit</th>
            </tr>
            @foreach ($games as $game)
            <tr>
                <td>{{ date('Y.m.d', strtotime($game->starts_at)); }}</td>
                <td>{{ $game->name; }}</td>
                <td>{{ $game->country_name }}, {{ $game->region_name; }}</td>
                <td>{{ $game->total_booked; }}</td>
                <td>{{ $game->total_earned; }}</td>
                <td><a href="{{ URL::route('game-edit', $game->id) }}">edit</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@stop