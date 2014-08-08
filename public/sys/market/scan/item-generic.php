<?php

class ScanMarketItem
{
	public $type    = 1;		    // by default you cannot buy the item, only scan
	public $id      = 0;			// internal ID, always integer
	public $stock   = 1;			// by default all the items are in stock
	public $name    = '';
	public $descr   = '';
	public $img     = '';
	public $code    = '';			// article ID, can be string
	public $url     = '';
	public $price   = 0;

	protected $textPosition = 0;

    public function __construct($type = 1)
    {
        $this->type = $type;
    }

    private function rq($t)
	{
		return str_replace(array("\\", '&qu'), array('&#92;', '&qqu;'), htmlspecialchars(trim($t)));
	}
  
	public function jsonp()
	{
		$res = '{';
		$res.= '"type": "'  . $this->rq($this->type) . '",';
		$res.= '"id": "'    . $this->rq($this->id) . '",';
		$res.= '"stock": "' . $this->rq($this->stock) . '",';
		$res.= '"name": "'  . $this->rq($this->name) . '",';
		$res.= '"descr": "' . $this->rq($this->descr) . '",';
		$res.= '"img": "'   . $this->rq($this->img) . '",';
		$res.= '"code": "'  . $this->rq($this->code) . '",';
		$res.= '"url": "'   . $this->rq($this->url) . '",';
		$res.= '"price": "' . $this->rq($this->price) . '"';
		$res.= '}';

		return $res;
	}

	public function grab($from, $t1, $t2, $plus=0)
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

	public function grabFrom($position)
	{
		$this->textPosition = $position;
	}
}