@extends('layout')

@section('content')
<div class="dialog-box-1">
    Your email validation failed. Please contact <a href="mailto:{{ Config::get('app.emails.support') }}?subject=Data usage">{{ Config::get('app.emails.support') }}</a> to find out the reason.
</div>
@stop