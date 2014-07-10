@extends('layout')

@section('title')
| Game presentation
@stop

@section('header')
{{ HTML::style('/css/games.css') }}
<style>

</style>
@stop

@section('content')

<div class="window-box-1">
    @if ($map)
    <iframe class="map-frame" src="{{ $map }}" width="100%" height="480"></iframe>
    @else
    No map present for this game.
    @endif
</div>
@stop