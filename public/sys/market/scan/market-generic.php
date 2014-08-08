<?php

require_once 'item-generic.php';

class ScanMarket
{
	public $shopName = '';							// official shop name
	public $shopLogic = 1;							// 1 - AND; 2 - OR; 3 - complex; 4 - exact phrase
	public $itemsType = 1;							// 1 - search; 2 - search & buy
    
    protected $debug = false;						// for in-vivo debugging
	protected $showNoStock = false;					// set TRUE to allow items being out of stock
	protected $showNoPrice = false;					// set TRUE to allow items with unknown price
	protected $startedAt = 0;						// execution start microtime
	protected $finishedAt = 0;						// execution end microtime
	protected $isError = false;						// was there any arror
	protected $currRow = 0;							// current item
	protected $maxRows = 30;						// maximum search items for this shop
	protected $tooMuchFound = false;				// TRUE when found more than 'maxRows' items
	protected $items = null;						// found items
	protected $query = '';							// search query
	protected $queryRaw = '';						// raw search query (for manual processing)
	protected $charset = 'UTF-8';					// grabbed page charset
	protected $minPrice = 0;						// minimum price, can be either submitted or postfiltered
	protected $maxPrice = 0;						// maximum price, --//--
	protected $category = 0;						// 0 - all, 1 - rifles
  
	protected $textPosition = 0;					// used as temporary "file pointer"
    
	public function __construct($name, $id, $char = 'UTF-8', $type = 1)
	{
		$this->startedAt = microtime(true);
		$this->shopName  = $name;
		$this->itemsType = $type;
		$this->charset   = $char;
		$this->items     = array();
    
		if (isset ($_GET['logic'])) $this->shopLogic = (int) $_GET['logic'];
		if (isset ($_GET['showNoStock'])) $this->showNoStock = (int) $_GET['showNoStock'];
		if (isset ($_GET['showNoPrice'])) $this->showNoPrice = (int) $_GET['showNoPrice'];
		if (isset ($_GET['maxRow'])) $this->maxRows = (int) $_GET['maxRow'];
		if (isset ($_GET['minPrice'])) $this->minPrice = (int) $_GET['minPrice'];
		if (isset ($_GET['maxPrice'])) $this->maxPrice = (int) $_GET['maxPrice'];
		if (isset ($_GET['category'])) $this->category = (int) $_GET['category'];
		if (isset ($_GET['searchText']))
		{
			$this->queryRaw = $_GET['searchText'];
			$this->query = str_replace(' ', '+', $_GET['searchText']);
		}
    
		if ($this->charset != 'UTF-8')	$this->query = iconv('UTF-8', $this->charset, $this->query);
	}
  
	protected function grabFrom($position)
	{
		$this->textPosition = $position;
	}
  
    /**
     * Returns text, memorizes last position.
     * Use empty T2 to check presence of T1
     *
     * @param $from
     * @param $t1
     * @param $t2
     * @param int $plus
     * @return null|string
     */
    protected function grab($from, $t1, $t2, $plus = 0)
	{
		$m1 = strpos($from, $t1, $this->textPosition);

		if ($m1 === false)
        {
            return null;
        }

		if (!$t2)
		{
			$this->textPosition = $m1;
			return 'OK';
		}

		$m2 = strpos($from, $t2, $m1);
		$this->textPosition = $m2;
		$ofs = strlen($t1) + $plus;

		return trim(substr($from, $m1 + $ofs, $m2 - $m1 - $ofs));
	}
  
	protected function getPage($url)
	{
		$options = array
        (
            'http' => array
            (
                'timeout'       => 30,
                'user_agent'    => 'airsoft.zone',
                'max_redirects' => 10,
            )
        );

		$context = stream_context_create($options);
		$page = @file_get_contents($url, false, $context);

		if ($page === false)
        {
            return null;
        }

    	if ($this->charset != 'UTF-8')
        {
            $page = iconv($this->charset, 'UTF-8', $page);
        }

		return $page;
	}
	
	protected function postPage($url, $payload)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$page = curl_exec($ch);
		curl_close($ch);

		if ($page === false)
        {
            return null;
        }

    	if ($this->charset != 'UTF-8')
        {
            $page = iconv($this->charset, 'UTF-8', $page);
        }

		return $page;
	}
	
	protected function fullstop($str)
	{
        // add necessary symbols later
		return trim(trim($str), ',.') . '.';
	}

	protected function scanner()
	{
	}

    /**
     * returns negative value if this is the last line
     *
     * @param $item
     * @return int
     */
    protected function pushRow($item)
	{
        // skip priceless item OR skip out of stock item
		if (!$this->showNoPrice && !$item->price || !$this->showNoStock && !$item->stock)
        {
            return 0;
        }

        // be accurate here
		if ($this->category == 1)
		{
			if ($item->price && $item->price < 2000)
            {
                return 0;
            }

			$str = strtolower($item->name);

			if
			(
				mb_strpos($str, 'пулемет') === false	&&
				mb_strpos($str, 'автомат') === false	&&
				mb_strpos($str, 'винтовк') === false
			)
			return 0;
		}
		
		if ($this->minPrice > 0 && $item->price < $this->minPrice || $this->maxPrice > 0 && $item->price > $this->maxPrice)
        {
            return 0;
        }

		// item post-filters
		if ($item->code == '')
        {
            $item->code = 'N' . $item->id;
        }

		array_push($this->items, $item);
		$this->currRow++;

		if ($this->debug)
		{
			echo $this->currRow . ':' . $this->maxRows . "\n";
		}

        // 'maxRows' reached
		if ($this->currRow >= $this->maxRows)
        {
            return -1;
        }

        // may continue scanning
		return 1;
	}
  
	public function scan()
	{
		$this->scanner();
		$this->finishedAt = microtime(true);
		
		//	shop post-filters
	}

    /**
     * Fast JSONP assembling
     *
     * @return string
     */
    public function jsonp()
	{
		if (!$this->finishedAt)
        {
            $dt = 0;
        }
		else
        {
            $dt = sprintf("%01.3f", $this->finishedAt - $this->startedAt);
        }
    
		$res = '_jqjsp({"shopName":"' . $this->shopName . '",';
		$res.= '"body": [';
    
		$max = sizeof($this->items);
		for ($i = 0; $i < $max; $i++)
		{
			$res .= $this->items[$i]->jsonp();
			if ($i < $max - 1)
            {
                $res .= ',';
            }
		}
    
		$res.= '],';
		$res.= '"isError": "'  . ($this->isError ? '1':'0') . '",';
		$res.= '"logic": "'    . $this->shopLogic . '",';
		$res.= '"elapsed": "'  . $dt . '",';
		$res.= '"over9000": "' . ($this->tooMuchFound ? '1':'0') . '"});';
        
		return $res;
	}
}