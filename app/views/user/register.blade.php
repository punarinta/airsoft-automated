@extends('layout')

@section('title')
| Register
@stop

@section('header')
{{ HTML::style('/css/home.css') }}
@stop

@section('content')
<fieldset class="dialog-box-1" style="width:280px">
    <legend>Sign-up</legend>
    <table id="register-form">
        <tr>
            <td>Email:</td>
            <td>&nbsp;</td>
            <td><input class="my-input email" type="text" autofocus></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <button class="my-btn submit" onclick="signUp()">Sign-up</button>
            </td>
        </tr>
    </table>
</fieldset>
<script>
function signUp()
{
    var re = /\S+@\S+\.\S+/, email = $('#register-form .email').val().trim()
    if (!re.test(email))
    {
        alert('Sorry, that´s not an email.')
        return false
    }

    az.ajaxVerbosity = 1
    az.ajaxPost('user', JSON.stringify({email:email,autologin:1}), function(data)
    {
        if (data)
        {
            alert('Thanks. Check your email for a password.')
            document.location.reload()
        }
    })
}
$('#register-form .email').keypress(function(e)
{
    if (e.keyCode == 13) signUp()
})
</script>
@stop