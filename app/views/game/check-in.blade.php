@extends('layout')

@section('title')
| Check-in
@stop

@section('header')
<style>
#barcode {font-size:36px;width:200px}
/*#btn-check-in {background:#ff1a00;color:#333;border-color:#cc0000}
#btn-check-in:hover {border-color:#b02b2c;color:#111}*/
.ticket-validity {font-size:36px;display:none;padding-top:10px}
#ticket-invalid {color:red}
#ticket-valid {color:#008000}
#player-list {width:100%}
#player-list td:nth-child(1n+2) {text-align: center}
/*#player-list td:nth-child(3):hover {background:#e8f0ff;cursor:pointer}*/
.my-btn.big {font-size:20px}
#player-list td.red {background-color:#ff3a20}
#player-list span.code {color:#999}
.checked {color: #02b500}
</style>
@stop

@section('content')
<div class="window-box-1">
    <input type="text" class="my-input" id="barcode" maxlength="10" placeholder="XXXXXXXX" autofocus/>
    <div>
        <button id="btn-check-in" class="my-btn big">Check-in</button>
        <button id="btn-validate" class="my-btn big">Validate</button>
    </div>
    <div id="ticket-valid" class="ticket-validity">Ticket is valid</div>
    <div id="ticket-invalid" class="ticket-validity">Ticket is invalid</div>
    <hr class="my-hr"/>
    <table id="player-list" class="my-table">
        <tr>
            <th>Name</th>
            <th>Team</th>
            <th>Party</th>
            <th>Pays in cash</th>
            <th>Ticket paid</th>
            <th>Checked-in</th>
        </tr>
        @foreach($tickets as $ticket)
        <tr class="ticket-{{ $ticket->id }}">
            <td class="{{ $ticket->ticket_status & Ticket::STATUS_CHECKED ? 'checked' : '' }}">{{ $ticket->nick }}<span class="code">, {{ strtoupper(str_pad(Bit::base36_encode(Bit::swap15($ticket->id)), 8, '0', STR_PAD_LEFT)) }}</span></td>
            <td>{{ $ticket->team_name }}</td>
            <td>{{ $ticket->game_party_name }}</td>
            <td>{{ $ticket->is_cash ? '+' : '–' }}</td>
            <td class="{{ $ticket->ticket_status & Ticket::STATUS_PAID ? '' : 'red' }}">{{ $ticket->ticket_status & Ticket::STATUS_PAID ? '+' : '–' }}</td>
            <td>{{ $ticket->ticket_status & Ticket::STATUS_CHECKED ? '+' : '–' }}</td>
        </tr>
        @endforeach
    </table>
</div>
<script>
function validate()
{
    az.ajaxGet('ticket/validate', $('#barcode').val(), function(data)
    {
        if (data.exists)
        {
            $('#ticket-invalid').hide()
            $('#ticket-valid').show()
//            $('.ticket-' + data.id)[0].scrollIntoView( true )
        }
        else
        {
            $('#ticket-valid').hide()
            $('#ticket-invalid').show()
        }
    })
}
$('#barcode').keypress(function(e)
{
    if (e.keyCode == 13)
    {
        validate()
    }
})

$('#btn-validate').click(validate)

$('#btn-check-in').click(function()
{
    az.ajaxGet('ticket/check-in', $('#barcode').val(), function(data)
    {
        $('td:eq(0)', '.ticket-' + data.id).addClass('checked')
        $('td:eq(3)', '.ticket-' + data.id).html('+')
    })
})
</script>
@stop