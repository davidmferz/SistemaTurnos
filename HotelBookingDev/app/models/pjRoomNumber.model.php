<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjRoomNumberModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'rooms_numbers';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'room_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'number', 'type' => 'varchar', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjRoomNumberModel($attr);
	}
}
?>