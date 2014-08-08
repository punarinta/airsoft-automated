<?php
require_once 'market-generic.php';

class Strikerairsoft_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://www.strikerairsoft.se/se/search.php?show=searchform&text=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Antal träffar:&nbsp;0', null))
        {
            return 2;
        }

        $this->grab($html, 'articleListTable', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<tr valign="bottom" class', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->id = $this->grab($html, ' article-', '">');
            $item->url = 'http://www.strikerairsoft.se' . $this->grab($html, 'a href="', '">');

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
            $item->name = trim(strip_tags($this->grab($html, 'listArticleName">', '</a>')));
            $item->descr = trim(strip_tags($this->grab($html, 'listArticleTextContainer">', '</div>')));
            $item->price = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, 'listArticlePrice">', '</span>'));

            $data = $this->grab($html, 'listLineBuyButtonColumn', '</td>');

            if (strpos($data, '"Bevaka"') !== false)
            {
                $item->stock = 0;
            }

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Strikerairsoft_Se('Striker Airsoft', 1, 'UTF-8');
$shop->scan();
echo $shop->jsonp();