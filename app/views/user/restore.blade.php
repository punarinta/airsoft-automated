@extends('layout')

@section('content')
<fieldset class="dialog-box-1" style="width:280px">
    <legend>Remind password</legend>
    <form action="{{ action('UserController@restoreFormEndpoint') }}" method="POST">
        <table>
            <tr>
                <td>Your email:</td>
                <td>&nbsp;</td>
                <td><input class="my-input" type="email" name="email" autofocus></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <input class="my-btn" type="submit" value="Send to email">
                </td>
            </tr>
        </table>
    </form>
</fieldset>
@stop