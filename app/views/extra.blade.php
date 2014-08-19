@extends('layout')

@section('title')
| {{ trans('airsoft.extra.title') }}
@stop

@section('header')
<style>
#tools-list {}
.dialog-box-1 {padding:5px 25px 15px}
</style>
@stop

@section('content')
<div class="dialog-box-1">
    <h2>{{ trans('airsoft.extra.head') }}</h2>
    <ul id="tools-list">
        <li><a class="my-link" href="{{ URL::route('market') }}">{{ trans('airsoft.extra.item-market') }}</a></li>
        <li><a class="my-link" href="{{ URL::route('tool-fps') }}">{{ trans('airsoft.extra.item-tools-fps') }}</a></li>
    </ul>
</div>
@stop