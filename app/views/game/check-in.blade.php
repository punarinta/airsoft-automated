@extends('layout')

@section('header')
<style>
#barcode {font-size:36px;width:200px}
#btn-check-in {background:#ff1a00;color:#333;border-color:#cc0000}
#btn-check-in:hover {border-color:#b02b2c;color:#111;}
.ticket-validity {font-size:36px;display:none;padding-top:10px}
#ticket-invalid {color:red}
#ticket-valid {color:#008000}
#player-list {width:100%}
#player-list td:nth-child(1n+2) {text-align: center}
/*#player-list td:nth-child(3):hover {background:#e8f0ff;cursor:pointer}*/
.my-btn.big {font-size:20px}
</style>
@stop

@section('content')
<div class="window-box-1">
    <input type="text" class="my-input" id="barcode" maxlength="10" placeholder="0000000000" autofocus/>
    <div>
        <button id="btn-check-in" class="my-btn big">Check-in</button>
        <button id="btn-validate" class="my-btn big">Validate</button>
    </div>
    <div id="ticket-valid" class="ticket-validity">Ticket is valid</div>
    <div id="ticket-invalid" class="ticket-validity">Ticket is invalid</div>
    <hr class="my-hr"/>
    <table id="player-list" class="my-table">
        <tr>
            <th>Player</th>
            <th>Pays in cash</th>
            <th>Checked-in</th>
        </tr>
        @foreach($tickets as $ticket)
        <tr class="ticket-{{ $ticket->id }}">
            <td>{{ $ticket->nick }} [{{ $ticket->team_name }}]</td>
            <td>{{ $ticket->is_cash ? '+' : '–' }}</td>
            <td>{{ $ticket->ticket_status == Ticket::STATUS_CHECKED ? '+' : '–' }}</td>
        </tr>
        @endforeach
    </table>
</div>
<script>
function validate()
{
    var code = $('#barcode').val()
    if (code.length == 10)
    {
        az.ajaxGet('ticket/validate', code, function(data)
        {
            if (data.exists)
            {
                $('#ticket-invalid').hide()
                $('#ticket-valid').show()
            }
            else
            {
                $('#ticket-valid').hide()
                $('#ticket-invalid').show()
            }
        })
    }
    else alert('Code should contain 10 digits')
}
// allow digits only
$('#barcode').bind('change keyup', function()
{
    $(this).val($(this).val().replace(/[^\d]/g, ''))
}).keypress(function(e)
{
    if (e.keyCode == 13)
    {
        validate()
    }
})

$('#btn-validate').click(validate)

$('#btn-check-in').click(function()
{
    var code = $('#barcode').val()
    if (code.length == 10)
    {
        az.ajaxGet('ticket/check-in', code, function(data)
        {
            $('td:eq(2)', '.ticket-' + data.id).html('+')
        })
    }
    else alert('Code should contain 10 digits')
})
</script>
@stop