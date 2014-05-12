@extends('layout')

@section('content')

<div class="dialog-box-1">
    You are not yet arranging any game. But it's easy <a href="{{ URL::route('games-edit') }}">to start</a>.
</div>

@stop