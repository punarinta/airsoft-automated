@extends('layout')

@section('header')
{{ HTML::style('/css/profile.css') }}
@stop

@section('content')
<div class="padded-content">
    <fieldset class="my-fieldset">
        <legend>Status</legend>
        @if (Auth::user()->getIsValidated())
        You can organize games.
        @else
        You cannot organize games. <a href="{{ URL::route('organizer-dashboard') }}">Read here how to become an organizer.</a>
        @endif
    </fieldset>

    <br/>

    <fieldset class="my-fieldset" style="display: inline" id="form-profile">
        <legend>Your profile</legend>
        <table>
            <tr>
                <td>Nick:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input nick" value="{{ $nick }}"/></td>
            </tr>
            <tr>
                <td>Birthday:</td>
                <td>&nbsp;</td>
                <td><input type="date" class="my-date birth-date" value="{{ $birth_date }}"/></td>
            </tr>
            <tr>
                <td>Team:</td>
                <td>&nbsp;</td>
                <td>
                    @include('partial/team-picker', ['placement' => 'vertical', 'defaults' => [$team_country_id, $team_region_id, $team_id], 'prefix' => 'me_'])
                    <input type="hidden" class="team-id" value="{{ $team_id }}"/>
                </td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <a href="#" class="my-btn save">Save profile</a>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="my-fieldset" style="display: inline" id="form-team">
        <legend>Your team</legend>
        <table>
            @if ($team_editable)
            <tr>
                <td>Name:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input name" value="{{ $team_name }}"/></td>
            </tr>
            <tr>
                <td>Region:</td>
                <td>&nbsp;</td>
                <td>
                    @include('partial/region-picker', ['placement' => 'vertical', 'defaults' => [$team_country_id, $team_region_id,], 'prefix' => 'team_'])
                    <input type="hidden" class="team-id" value="{{ $team_id }}"/>
                </td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <a href="#" class="my-btn save">Save team</a>
                </td>
            </tr>
            @else
            <tr>
                <td>Name:</td>
                <td>&nbsp;</td>
                <td>{{ $team_name }}</td>
            </tr>
            <tr>
                <td>Region:</td>
                <td>&nbsp;</td>
                <td>{{ $team_country_name }}, {{ $team_region_name }}</td>
            </tr>
            @endif
        </table>
    </fieldset>
</div>

<script>
    $('#form-profile .save').click(function()
    {
        $.ajax(
        {
            url: '/api/user/{{ $user_id }}',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(
            {
                nick: $('#form-profile .nick').val(),
                birth_date: $('#form-profile .birth-date').val(),
                team_id: me_team_picker.getTeamId()
            }),
            success: function(json)
            {
                alert(json.errMsg ? json.errMsg : 'Saved')
            }
        })

        return false
    })

    $('#form-team .save').click(function()
    {
        @if ($team_editable)
        $.ajax(
        {
            url: '/api/team/{{ $team_id }}',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(
            {
                name: $('#form-team .name').val(),
                region_id: team_region_picker.getLocation()[1]
            }),
            success: function(json)
            {
                alert(json.errMsg ? json.errMsg : 'Saved')
            }
        })
        @else
        $.ajax(
        {
            url: '/api/team',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(
            {
                name: $('#form-team .name').val(),
                region_id: team_region_picker.getLocation()[1]
            }),
            success: function(json)
            {
                alert(json.errMsg ? json.errMsg : 'Saved')
            }
        })
        @endif

        return false
    })
</script>
@stop