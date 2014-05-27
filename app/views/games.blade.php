@extends('layout')

@section('title')
| Games
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="window-box-1">
    <div id="calendar">
        <div id="calendar-bar">
            @include('partial/region-picker', ['placement' => 'horizontal', 'defaults' => [1,0], 'prefix' => 'games_', 'style' => 'float:left'])
            <a class="my-btn" style="float:right" href="{{ URL::route('game-edit') }}">Create your own!</a>
        </div>
        <table class="my-table">
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Region</th>
                <th>Booking</th>
            </tr>
            @foreach ($games as $game)
            <tr class="region-{{$game->region_id}}">
                <td>{{ date('Y.m.d', strtotime($game->starts_at)) }}</td>
                <td>{{ $game->name }}</td>
                <td>{{ $game->country_name }}, {{ $game->region_name; }}</td>
                <td>
                    @if ($game->editable)
                    <a href="{{ URL::route('game-edit', $game->id) }}">edit</a>
                    @elseif ($game->is_booked)
                    <a href="{{ URL::route('game-briefing', $game->id) }}">booked</a>
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
<script>
games_region_picker.change(function(geo)
{
    if (geo[1] - 0)
    {
        $('#calendar tr:gt(0)').hide()
        $('#calendar tr.region-' + geo[1]).show()
    }
    else
    {
        $('#calendar tr').show()
    }
})
</script>
@stop