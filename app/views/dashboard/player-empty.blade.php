@extends('layout')

@section('content')
<div class="dialog-box-1">
    You are not participating in any game. Please go to <a href="{{ URL::route('games') }}">games list</a> to book tickets.
</div>
@stop