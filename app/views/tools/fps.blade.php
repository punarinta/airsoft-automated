@extends('layout')

@section('title')
| fps/ms converter
@stop

@section('content')
<style type="text/css">
#dialog {padding:20px}
#dialog .my-input {width:80px;text-align:center;font-size:18px}
#dialog table {text-align:center;margin-bottom:15px}
#btn-aux {font-size:11px}
#aux {display:none;margin-top:10px}
</style>
<div class="dialog-box-1" id="dialog">
    <p>
        This tool converts velocity measured in feet per second (fps) to meters per second (m/s). <br/>
        Just enter the known value and get the result immediately.
    </p>
    <table>
        <tr><td>FPS</td><td>&nbsp;</td><td>m/s</td></tr>
        <tr>
            <td><input id="sp1" class="my-input" maxlength="5" value="300"></td>
            <td>&lt;=&gt;</td>
            <td><input id="sp2" class="my-input" maxlength="5" value="91.44"></td>
        </tr>
    </table>
    <a id="btn-aux" href="#">extra info</a>
    <div id="aux">BB with a mass of &nbsp;<input id="sp3" class="my-input" maxlength="5" value="0.2">&nbsp; gram will have energy of <span id="J">0.8281</span> J.</div>
</div>
<script type="text/javascript">
var v,m
$('#sp1,#sp2').keyup(function(){
v=parseInt($(this).val(),10)
if(!v)return
if($(this).attr('id')=='sp1')$('#sp2').val((v/3.28084).toString().substr(0,5))
else $('#sp1').val((v*3.28084).toString().substr(0,6))
$('#sp3').keyup()})
$('#sp3').keyup(function(){
v=parseInt($('#sp2').val(),10)
m=parseFloat($(this).val())
if(!m)return
$('#J').html((v*v*m/2000).toString().substr(0,6))})
$('#sp1').focus()
$('#btn-aux').click(function(){
$('#aux').slideToggle(400)
return false})
</script>
@stop