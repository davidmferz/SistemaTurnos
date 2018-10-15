<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjUtil extends pjToolkit
{
	static public function getDiscount($amount, $room_id, $voucher=NULL)
	{
		if (is_null($voucher) || !isset($voucher) || empty($voucher))
		{
			return 0;
		}
		
		$discount = 0;
		if (isset($voucher['voucher_rooms']['all'])
			|| in_array($room_id, array_keys($voucher['voucher_rooms']))
		)
		{
			foreach ($voucher['voucher_rooms'] as $rid => $item)
			{
				if (!in_array($rid, array('all', $room_id)))
				{
					continue;
				}
				switch ($item['voucher_type'])
				{
					case 'percent':
						$discount = ($amount * $item['voucher_discount']) / 100;
						break;
					case 'amount':
						$discount = $item['voucher_discount'];
						break;
				}
			}
		}
		
		return $discount;
	}
	
	static public function getReferer()
	{
		if (isset($_GET['_escaped_fragment_']))
		{
			if (isset($_SERVER['REDIRECT_URL']))
			{
				return $_SERVER['REDIRECT_URL'];
			}
		}
		
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$pos = strpos($_SERVER['HTTP_REFERER'], "#");
			if ($pos !== FALSE)
			{
				// IE fix
				return substr($_SERVER['HTTP_REFERER'], 0, $pos);
			}
			return $_SERVER['HTTP_REFERER'];
		}
	}
	
	static public function uuid()
	{
		return chr(rand(65,90)) . chr(rand(65,90)) . time();
	}
	
	static public function sortMultiDimensionsArray(&$array, $key)
	{
		$sorter = array();
		$ret = array();
		reset($array);
		foreach ($array as $k => $val) 
		{
			$sorter[$k]=$val[$key];
		}
		asort($sorter);
		foreach ($sorter as $k => $val) 
		{
			$ret[$k]=$array[$k];
		}
		$array=$ret;
	}
	
	public static function printNotice($title, $body, $convert = true, $close = true, $autoClose = false)
	{
		if (!$autoClose)
		{
			parent::printNotice($title, $body, $convert, $close);
		} else {
			?>
			<div class="notice-box notice-fancy">
				<div class="notice-middle">
					<span class="notice-success">&nbsp;</span>
					<div class="notice-content">
						<?php
						if (!empty($title))
						{
							printf('<div class="notice-title">%s</div>', $convert ? htmlspecialchars(stripslashes($title)) : stripslashes($title));
						}
						if (!empty($body))
						{
							printf('<div class="notice-body">%s</div>', $convert ? htmlspecialchars(stripslashes($body)) : stripslashes($body));
						}
						if ($close)
						{
							?><a href="#" class="notice-close"></a><?php
						}
						?>
						<div class="notice-progress-wrap">
							<div class="notice-progress"></div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	
	public static function toMomemtJS($format)
	{
		$f = str_replace(
				array('Y', 'm', 'n', 'd', 'j'),
				array('yyyy', 'mm', 'm', 'dd', 'd'),
				$format
		);
	
		return $f;
	}
}
?>