@extends('layout')

@section('title')
| {{ trans('about.title') }}
@stop

@section('content')
<div class="padded-content" style="max-width:800px;background:#fff;margin-top:80px">
{{ sprintf(trans('about.text'), Config::get('app.emails.office'), Config::get('app.emails.office')) }}
</div>
@stop