var az =
{
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
    ajaxPost: function(object, data)       // creation
    {
        $.ajax(
        {
            url: '/api/' + object,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: az.ajaxResult
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
        alert(json.errMsg ? json.errMsg : 'Saved')
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