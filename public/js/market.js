var market =
{
    init: function()
    {
        $('#btn-search').click(function()
        {
            var what = $('#inp-search').val().trim()
            market.search(what)
        })
    },

    search: function(what)
    {
        var i, store

        for (i in stores)
        {
            store = stores[i]
            $.ajax(
            {
                url: '/sys/market/scan/' + store.scan + '.php?showNoStock=1&showNoPrice=1&searchText=' + encodeURIComponent(what),
                type: 'GET',
                success: function(json)
                {
                    json = JSON.parse(json.replace(/(\r\n|\n|\r)/gm, "<br>"))

                    var j, item, html, price, img
                    for (j in json.body)
                    {
                        item = json.body[j]

                        price = item.price
                        if (price - 0 === 0) price = '&mdash;'

                        if (item.img.length)
                        {
                            img = '<img class="item-icon" src="' + item.img + '" onerror=market.imageCheck(this)>'
                        }
                        else img = '&mdash;'

                        html = '<tr>'
                        html += '<td>' + item.name + '</td>'
                        html += '<td>' + price + '</td>'
                        html += '<td>' + store.name + '</td>'
                        html += '<td>' + img + '</td>'
                        html += '</tr>'

                        $('#results tbody').append(html)
                    }
                }
            })
        }
    },

    imageCheck: function(obj)
    {
        $(obj).parent().html('<a target="_blank" href="' + $(obj).attr('src') + '">[ reload ]</a>')
    }
}

market.init()