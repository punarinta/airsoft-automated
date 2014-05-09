<?php
$style = isset ($style) ? $style : '';
$prefix = isset ($prefix) ? $prefix : '';
$defaults = isset ($defaults) ? $defaults : array(0,0);
?>

<div style="{{$style}}">
    <select id="{{$prefix}}country-ddb">
        <option value="0">Any</option>
    </select>
    <select id="{{$prefix}}region-ddb">
        <option value="0">Any</option>
    </select>
</div>
<script>
    var ddbC = $('#{{$prefix}}country-ddb'),
        ddbR = $('#{{$prefix}}region-ddb')

    $.get('/api/country', function(json)
    {
        var i, html = '', sel, defCountryId = {{$defaults[0]}}, defRegionId = {{$defaults[1]}};
        for (i in json.data)
        {
            sel = (json.data[i].id == defCountryId) ? 'selected' : ''
            html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
        }

        ddbC.html(ddbC.html() + html)

        if (!defRegionId)
        {
            // no default country => get the first from the list
            defRegionId = json.data[0].id
        }

        $.get('api/region/by-country/' + defCountryId, function(json)
        {
            var i, html = '', sel
            for (i in json.data)
            {
                sel = (json.data[i].id == defRegionId) ? 'selected' : ''
                html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
            }

            ddbR.html(ddbR.html() + html)
        })
    })
</script>