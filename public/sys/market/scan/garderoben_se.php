<?php
require_once 'generic-market.php';

class Garderoben_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://www.garderoben.se/searchresults?pagesize=60&searchstring=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, '</span> gav 0 tr', null))
        {
            return 2;
        }

        $this->grab($html, 'search-result-table"', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '</tr><tr id="', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->name = $this->grab($html, 'title="', '" c');
            $item->descr = $item->name;
            $item->img = 'http://www.garderoben.se' . str_replace('/thumbs/', '/large/', $this->grab($html, 'data-original="', '" '));
            $item->id = $this->grab($html, 'notifySelectToExternalSearchProvider(', ')');
            $item->url = 'http://www.garderoben.se' . $this->grab($html, 'href="', '">');
            $item->price = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, 'SearchPriceAmount">', '</span>'));

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Garderoben_Se('Garderoben', 'UTF-8');
$shop->scan();
echo $shop->jsonp();