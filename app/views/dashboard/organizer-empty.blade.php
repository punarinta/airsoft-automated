@extends('layout')

@section('title')
| Organizer dashboard
@stop

@section('content')
<div class="dialog-box-1" style="text-align:center">
    <p>
        <br/>
        You are not arranging any games yet. But it's easy to start.
    </p>
    <p>
        <a class="my-btn" href="{{ URL::route('game-edit') }}">Create your fist game!</a>
    </p>
</div>
@stop