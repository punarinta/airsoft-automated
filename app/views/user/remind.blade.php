@extends('layout')

@section('header')
@stop

@section('content')

<form action="{{ action('UserController@remindFormEndpoint') }}" method="POST">
    <input type="email" name="email">
    <input type="submit" value="Send Reminder">
</form>

@stop