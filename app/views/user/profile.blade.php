@extends('layout')

@section('title')
| Your profile
@stop

@section('header')
{{ HTML::style('/css/profile.css') }}
{{ HTML::style('/css/pikaday.css') }}
{{ HTML::script('/js/moment.min.js') }}
{{ HTML::script('/js/pikaday.min.js') }}
@stop

@section('content')
<div class="padded-content">
    <fieldset class="my-fieldset" style="float:left">
        <legend>Status</legend>
        @if (Auth::user()->getIsValidated())
        1. You have passed validation and can organize games.
        @else
        1. You cannot organize games. <a href="{{ URL::route('organizer-dashboard') }}">Read here how to validate yourself.</a>
        @endif
        <br/>
        @if (Auth::user()->getIsEmailValidated())
        2. You have passed email validation and can participate in games.
        @else
        2. You cannot participate in games. Check your email for a confirmation link.</a>
        @endif
        <br/>
        @if ($team_editable && $team_present)
        3. You can edit your team as it was you who added it to the system.
        @elseif ($team_present)
        3. You cannot edit your team, as you did not create it.
        @else
        3. You may create your team or join an existing one.
        @endif
    </fieldset>

    <fieldset class="my-fieldset" style="float:left" id="form-profile">
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
                <td><input type="text" class="my-input birth-date" value="{{ $birth_date }}"/></td>
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

    <fieldset class="my-fieldset" style="float:left" id="form-team">
        <legend>Your team</legend>
        <table>
            @if ($team_editable)
            <tr>
                <td>Name:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input name" value="{{ $team_name }}"/></td>
            </tr>
            <tr>
                <td>Location:</td>
                <td>&nbsp;</td>
                <td>
                    @include('partial/region-picker', ['placement' => 'vertical', 'defaults' => [$team_country_id, $team_region_id,], 'prefix' => 'team_'])
                    <input type="hidden" class="team-id" value="{{ $team_id }}"/>
                </td>
            </tr>
            <tr>
                <td>URL:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input url" value="{{ $team_url }}"/></td>
            </tr>

            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <a href="#" class="my-btn save">
                        @if ($team_id)
                        Save team
                        @else
                        Create team
                        @endif
                    </a>
                </td>
            </tr>
            @else
            <tr>
                <td colspan="3"><span class="warn-span">Only team creator can edit the team</span></td>
            </tr>
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
            success: az.ajaxResult
        })

        return false
    })

    $('#form-team .save').click(function()
    {
        @if ($team_id)
        $.ajax(
        {
            url: '/api/team/{{ $team_id }}',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(
            {
                name: $('#form-team .name').val(),
                url: $('#form-team .url').val(),
                region_id: team_region_picker.getLocation()[1]
            }),
            success: az.ajaxResult
        })
        @else

        if (!confirm('We recommend you to check that your team does not exist in the system. Have you done it?'))
        {
            return false
        }

        $.ajax(
        {
            url: '/api/team',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(
            {
                name: $('#form-team .name').val(),
                url: $('#form-team .url').val(),
                region_id: team_region_picker.getLocation()[1]
            }),
            success: az.ajaxResult
        })
        @endif

        return false
    })

    new Pikaday(
    {
        field: $('#form-profile .birth-date')[0],
        firstDay: 1,
        format: 'YYYY-MM-DD'
    })
</script>
@stop