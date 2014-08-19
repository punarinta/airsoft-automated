<?php
require_once 'generic-market.php';

class Miltac_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://miltac.se/shop/advanced_search_result.php?search_in_description=1&keywords=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Det finns inga produkter', null))
        {
            return 2;
        }

        $this->grab($html, 'productListTable"', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<td align="center"><a', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = $this->grab($html, 'href="', '">');
            $item->img = 'http://miltac.se/shop/' . $this->grab($html, '<img src="', '" ');
            $item->name = $this->grab($html, 'title="', '" w');
            $item->descr = $item->name;
            $item->price = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, '"right">', 'kr</td>'));

            $stock = $this->grab($html, '"right">', '</td>');
            $item->stock = ($stock === '0') ? 0 : 1;

            $code = explode('&', $item->url);

            $item->id = (int) preg_replace('/[^0-9,]/', '', $code[0]);
            $item->code = $item->id;

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Miltac_Se('Miltac', 'UTF-8');
$shop->scan();
echo $shop->jsonp();