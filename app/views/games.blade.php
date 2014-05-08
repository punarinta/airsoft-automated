@extends('layout')

@section('header')

@stop

@section('content')

@foreach ($games as $game)
{{ $game->getName(); }}
<br/>
@endforeach

@stop