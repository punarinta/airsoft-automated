@extends('layout')

@section('content')
<fieldset class="dialog-box-1" style="width:280px">
    <legend>Sign-in</legend>

    {{ Form::open(array('url' => 'user/login', 'method' => 'post')) }}
    <table>
        <tr>
            <td>{{ Form::label('username', 'Username:') }}</td>
            <td>&nbsp;</td>
            <td><input class="my-input" name="username" type="text" id="username" value="{{ Input::old('username') }}" autofocus></td>
        </tr>
        <tr>
            <td>{{ Form::label('password', 'Password:') }}</td>
            <td>&nbsp;</td>
            <td><input class="my-input" name="password" type="password" value="" id="password"></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <input class="my-btn" type="submit" value="Login">
                <a href="{{ URL::route('user-restore-password') }}" class="my-btn" >Forgot password</a>
                <a href="{{ URL::route('user-register') }}" class="my-btn" >Register</a>
            </td>
        </tr>
    </table>
    {{ Form::close() }}
</fieldset>

@stop