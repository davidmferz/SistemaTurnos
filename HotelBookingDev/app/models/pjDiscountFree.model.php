<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjDiscountFreeModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'discount_frees';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'room_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'min_length', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'max_length', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'free_nights', 'type' => 'tinyint', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new self($attr);
	}
}
?>