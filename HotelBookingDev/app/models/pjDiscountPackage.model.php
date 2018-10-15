<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjDiscountPackageModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'discount_packages';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'room_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'start_day', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'end_day', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'total_price', 'type' => 'decimal', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new self($attr);
	}
}
?>