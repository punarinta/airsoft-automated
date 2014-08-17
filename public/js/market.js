var market =
{
    currentItem: null,
    documentTitle: document.title,

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
                $('#btn-search').text('Searching...')
                market.search(what)
            }
        }

        $('#btn-search').click(go)
        $('#inp-search').keypress(function(e)
        {
            if(e.keyCode == 13) go()
        }).keyup(function()
        {
            var q = $(this).val().trim()
            if (!q.length) document.title = market.documentTitle
            else document.title = 'Search for: ' + q
            market.setupHash()
        })

        $('#results table').on('click', 'tr', function()
        {
            market.currentItem = JSON.parse(decodeURIComponent($(this).data('item')))

            $('#item-viewer').show()
                .find('.content').html(market.currentItem.descr).show()
                .find('img').on('error', function()
            {
                $(this).hide()
            })
            $('#results').hide()
        })

        $('#item-viewer .back').click(function()
        {
            $('#results,#item-viewer').toggle()
        })

        $('#item-viewer .open').click(function()
        {
            window.open(market.currentItem.url, '_blank')
        })

        var q = market.getHash('q')
        if (q.length)
        {
            setTimeout(function(){$('#inp-search').val(q);go()}, 500)
        }
    },

    setupHash: function()
    {
        var hash = '', q = $('#inp-search').val().trim()
        if (q.length) hash = 'q=' + q
        location.hash = hash
    },

    getHash: function(k)
    {
        var i, p, h = window.location.hash.substr(1)
        h = h.split('&')
        for (i in h)
        {
            p=h[i].split('=')
            if (p.length == 2 && p[0] == k) return p[1]
        }
        return ''
    },

    search: function(what)
    {
        var i, scanCounter = 0

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
                                img = '<a target="_blank" href="' + item.img + '"><img class="item-icon" title="Open in original size" src="' + item.img + '" onerror=market.reloadImage(this)></a>'
                            }
                            else img = '&mdash;'

                            // hellfire
                            item.url = item.url.replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"').replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')

                            item.name = item.name.replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')
                            item.descr = item.descr.replace(/(&lt;)/gm, '<').replace(/(&gt;)/gm, '>').replace(/(&qqu;ot;)/gm, '"').replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')

                            name = item.name

                            html = '<tr data-item="'+encodeURIComponent(JSON.stringify(item))+'">'
                            html += '<td>' + name + '</td>'
                            html += '<td>' + price + '</td>'
                            html += '<td>' + stores[i].name + '</td>'
                            html += '<td>' + img + '</td>'
                            html += '</tr>'

                        //    console.log(item.descr)

                            $('#results table').show()
                            $('#btn-search').text('Find')

                            $('#results tbody').append(html)
                        }

                        scanCounter++
                        if (scanCounter == stores.length)
                        {
                            $('#btn-search').text('Find')
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