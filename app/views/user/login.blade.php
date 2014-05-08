@extends('layout')

@section('content')
<h1>Login</h1>

{{ Form::open(array('url' => 'user/login', 'method' => 'post')) }}

<!-- username field -->
<p>
    {{ Form::label('username', 'Username') }}<br/>
    {{ Form::text('username', Input::old('username')) }}
</p>

<!-- password field -->
<p>
    {{ Form::label('password', 'Password') }}<br/>
    {{ Form::password('password') }}
</p>

<!-- submit button -->
<p>{{ Form::submit('Login') }}</p>

{{ Form::close() }}
@stop