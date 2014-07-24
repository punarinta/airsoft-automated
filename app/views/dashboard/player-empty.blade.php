@extends('layout')

@section('title')
| {{ trans('airsoft.player-dash.title') }}
@stop

@section('content')
<div class="dialog-box-1" style="text-align:center">
    {{ sprintf(trans('airsoft.player-dash.empty-message'), '<a href="' . URL::route('games') . '">', '</a>') }}
</div>
@stop