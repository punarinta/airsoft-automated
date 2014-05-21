var az =
{
    ajaxVerbosity: 2,
    ajaxGet: function(object, id, callback)
    {
        $.ajax(
        {
            url: '/api/' + object + '/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(json)
            {
                if (json.errMsg) alert(json.errMsg)
                else if (callback) callback(json.data)
            }
        })
    },
    ajaxPost: function(object, data, callback)       // creation
    {
        $.ajax(
        {
            url: '/api/' + object,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(json)
            {
                az.ajaxResult(json)
                if (callback) callback(json.data)
            }
        })
    },
    ajaxPut: function(object, id, data)    // editing
    {
        $.ajax(
        {
            url: '/api/' + object + '/' + id,
            type: 'PUT',
            dataType: 'json',
            data: data,
            success: az.ajaxResult
        })
    },
    ajaxDelete: function(object, id)
    {
        $.ajax(
        {
            url: '/api/' + object + '/' + id,
            type: 'DELETE',
            dataType: 'json',
            success: az.ajaxResult
        })
    },
    ajaxResult: function(json)
    {
        console.log(az.ajaxVerbosity)

        if (az.ajaxVerbosity == 0) return
        if (az.ajaxVerbosity == 1 && json.errMsg) alert(json.errMsg)
        if (az.ajaxVerbosity == 2) alert(json.errMsg ? json.errMsg : 'Done.')
    },

    fillDdb: function(object, data)
    {
        var i, html = ''
        for (i in data)
        {
            html += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
        }

        $(object).html(html)
    }
}