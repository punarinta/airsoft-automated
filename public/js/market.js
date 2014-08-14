var market =
{
    init: function()
    {
        var go = function()
        {
            var what = $('#inp-search').val().trim()

            if (!what.length)
            {
                az.showModal('Please type in the search term')
            }
            else
            {
                $('#results tbody').html('')
                market.search(what)
            }
        }

        $('#btn-search').click(go)
        $('#inp-search').keydown(function(e)
        {
            if(e.keyCode == 13) go()
        })
    },

    search: function(what)
    {
        var i

        for (i in stores)
        {
            (function(i)
            {
                $.ajax(
                {
                    url: '/sys/market/scan/' + stores[i].scan + '.php?showNoStock=1&showNoPrice=1&searchText=' + encodeURIComponent(what),
                    type: 'GET',
                    success: function(json)
                    {
                        json = JSON.parse(json.replace(/(\r\n|\n|\r)/gm, "<br>"))

                        var j, item, html, price, img, name
                        for (j in json.body)
                        {
                            item = json.body[j]

                            price = item.price
                            if (price - 0 === 0) price = '&mdash;'

                            if (item.img.length)
                            {
                                img = '<a target="_blank" href="' + item.img + '"><img class="item-icon" src="' + item.img + '" onerror=market.reloadImage(this)></a>'
                            }
                            else img = '&mdash;'

                            item.name = item.name.replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')

                            if (item.descr.length)
                            {
                                name = '<div>' + item.name + '</div>'
                                name += '<div></div>'
                            }
                            else name = item.name

                            html = '<tr>'
                            html += '<td>' + name + '</td>'
                            html += '<td>' + price + '</td>'
                            html += '<td>' + stores[i].name + '</td>'
                            html += '<td>' + img + '</td>'
                            html += '</tr>'

                            $('#results tbody').append(html)
                        }
                    }
                })
            })(i);
        }
    },

    reloadImage: function(obj)
    {
        $(obj).parent().html('[ reload ]')
    }
}

market.init()