@extends('layout')

@section('content')
<div class="dialog-box-1">
    <h4>An error occurred:</h4>
    <p>{{ $exception->getMessage() }}</p>
</div>
@stop