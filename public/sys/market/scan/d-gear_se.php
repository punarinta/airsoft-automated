<?php
require_once 'generic-market.php';

class Dgear_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://www.d-gear.se/en/search?needle=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'No products found', null))
        {
            return 2;
        }

        $this->grab($html, '<div id="prodList">"', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<div class="itemCont', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $url = $this->grab($html, '<a href="', '">');
            $item->img = str_replace('/thumb/','/full/', 'http://www.d-gear.se' . $this->grab($html, '<img src="', '" />'));
            $item->vendor = $this->grab($html, '<p class="brand">', '</p>');

            $this->scanItem($url, $item);

            $id = explode('/', $url);
            $item->id = (int) end($id);

            $item->url = $url;

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }

    /**
     * Scans a separate item
     *
     * @param $url
     * @param $item
     */
    public function scanItem($url, &$item)
    {
        $scanner = new Dgear_Se('', 'UTF-8');
        $html = $this->getPage($url);

        $item->name = $scanner->grab($html, '<h1>', '</h1>');
        $item->price = (int) preg_replace('/[^0-9,]/', '', $scanner->grab($html, '<div id="appPrice" class="">', '</div>'));
        $item->descr = $scanner->grab($html, 'description bodyText">', '</div>');
    }
}

$shop = new Dgear_Se('D-Gear', 'UTF-8');
$shop->scan();
echo $shop->jsonp();