@extends('layout')

@section('title')
| {{ trans('airsoft.validate-org.title') }}
@stop

@section('content')
<div class="dialog-box-1">
    {{ sprintf(trans('airsoft.validate-org.text'), Config::get('app.emails.moderator'), Config::get('app.emails.moderator')) }}
</div>
@stop