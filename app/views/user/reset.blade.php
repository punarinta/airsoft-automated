@extends('layout')

@section('title')
| Reset password
@stop

@section('header')
<style>
#reset-dialogue {width:350px}
</style>
@stop

@section('content')
<div class="dialog-box-1" id="reset-dialogue">
    <form action="{{ action('UserController@resetFormEndpoint') }}" method="POST">
        <input type="hidden" name="token" value="{{ $token }}">
        <table>
            <tr>
                <td>Email:</td>
                <td><input class="my-input" type="email" name="email"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input class="my-input" type="password" name="password"></td>
            </tr>
            <tr>
                <td>Confirm&nbsp;password:</td>
                <td><input class="my-input" type="password" name="password_confirmation"></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><input class="my-btn" type="submit" value="Set password"></td>
            </tr>
        </table>
    </form>
</div>
@stop