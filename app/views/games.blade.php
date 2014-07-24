@extends('layout')

@section('title')
| {{ trans('airsoft.games.title') }}
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
@stop

@section('content')
<div class="window-box-1">
    <div id="calendar">
        <div id="calendar-bar">
            @include('partial/region-picker', ['placement' => 'horizontal', 'defaults' => [1,0], 'prefix' => 'games_', 'style' => 'float:left'])
            <a class="my-btn" style="float:right" href="{{ URL::route('game-edit') }}">{{ trans('airsoft.games.add-own') }}</a>
        </div>
        <table class="my-table">
            <tr>
                <th>{{ trans('airsoft.games.head-date') }}</th>
                <th>{{ trans('airsoft.games.head-name') }}</th>
                <th>{{ trans('airsoft.games.head-arranger') }}</th>
                <th>{{ trans('airsoft.games.head-region') }}</th>
                <th>{{ trans('airsoft.games.head-booking') }}</th>
            </tr>
            @foreach ($games as $game)
            <tr class="region-{{$game->region_id}}">
                <td>{{ date('Y.m.d', strtotime($game->starts_at)) }}</td>
                <td><a href="{{ URL::route('game-card', $game->id) }}">{{ $game->name }}</a></td>
                <td>{{ $game->organizer }}</td>
                <td>{{ $game->region_name }}, {{ $game->country_name }}</td>
                <td>
                    @if ($game->editable)
                    <a href="{{ URL::route('game-edit', $game->id) }}">{{ trans('airsoft.util.do-edit') }}</a>
                    @elseif ($game->is_booked)
                    <a href="{{ URL::route('game-briefing', $game->id) }}">{{ trans('airsoft.games.info-booked') }}</a>
                    @elseif ($game->bookable)
                    <a href="{{ URL::route('game-book', $game->id) }}">{{ trans('airsoft.util.do-book') }}</a>
                    @else
                    {{ trans('airsoft.games.no-tickets') }}
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