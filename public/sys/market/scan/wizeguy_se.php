<?php
require_once 'generic-market.php';

class Wizeguy_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->postPage('http://shop.wizeguy.se/se/search.php?id=9764', 'id=9764&op=search&text=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'gav inte något resultat', null))
        {
            return 2;
        }

        $this->grab($html, 'articleListTable', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<tr valign="bottom" class="articlegroup', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->id = $this->grab($html, ' article-', '">');
            $item->code = $item->id;

            $item->url = 'http://shop.wizeguy.se/' . $this->grab($html, '<a href="', '">') . '?ref_lp=airsoftzone';
            $item->img = $this->grab($html, '<img src="', '" ');
            $item->name = $this->grab($html, 'listArticleName">', '</a>');
            $item->descr = $item->name;

            $item->price = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, 'listArticlePrice">', ':'));

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Wizeguy_Se('Wizeguy', 'UTF-8');
$shop->scan();
echo $shop->jsonp();