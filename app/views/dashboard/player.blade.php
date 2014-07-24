@extends('layout')

@section('title')
| {{ trans('airsoft.player-dash.title') }}
@stop

@section('header')
{{ HTML::style('/css/dashboard.css') }}
@stop

@section('content')
<div class="window-box-1">
    <div id="calendar">
        <div id="calendar-bar">
            @include('partial/region-picker', ['placement' => 'horizontal', 'defaults' => [1,0], 'prefix' => 'games_', 'style' => 'float:left'])
        </div>
        <table class="my-table">
            <tr>
                <th>{{ trans('airsoft.player-dash.head-date') }}</th>
                <th>{{ trans('airsoft.player-dash.head-name') }}</th>
                <th>{{ trans('airsoft.player-dash.head-region') }}</th>
                <th>{{ trans('airsoft.player-dash.head-logistics') }}</th>
            </tr>
            @foreach ($games as $game)
            <tr class="region-{{$game->region_id}}">
                <td>{{ date('Y.m.d', strtotime($game->starts_at)) }}</td>
                <td>{{ $game->name }}</td>
                <td>{{ $game->country_name }}, {{ $game->region_name }}</td>
                <td><a href="{{ URL::route('game-briefing', $game->id) }}">{{ trans('airsoft.util.do-view') }}</a></td>
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
    else $('#calendar tr').show()
})
</script>
@stop