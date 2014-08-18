<?php
require_once 'generic-market.php';

class Tacticalstore_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->postPage('http://www.tacticalstore.se/se/search.php?id=40044', 'id=40044&op=search&text=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Antal träffar: 0', null))
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

            $item->url = 'http://tacticalstore.se' . $this->grab($html, '<a href="', '">');

            $img = $this->grab($html, '<img src="', '" ');
            $img = explode('.', $img);
            if (sizeof($img) > 3)
            {
                $ext = explode('_', $img[4]);
                $ext = $ext[0];
            }
            else
            {
                $ext = '';
            }

            $item->img = $img[0] . '.' . $img[1] . '.' . $img[2] . '.' . $ext;
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

$shop = new Tacticalstore_Se('Tactical Store', 'UTF-8');
$shop->scan();
echo $shop->jsonp();