@extends('layout')

@section('header')

@stop

@section('content')
<div class="padded_content">
    <table>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Region</th>
            <th>Can book</th>
        </tr>
        @foreach ($games as $game)
        <tr>
            <td>{{ date('Y.m.d', strtotime($game->starts_at)); }}</td>
            <td>{{ $game->name; }}</td>
            <td>{{ $game->region_name; }}</td>
            <td>{{ $game->bookable ? '+' : '–'; }}</td>
        </tr>
        @endforeach
    </table>
</div>


@stop