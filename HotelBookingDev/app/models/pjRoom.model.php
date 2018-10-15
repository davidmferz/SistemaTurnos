<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjRoomModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'rooms';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'adults', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'children', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'max_people', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'cnt', 'type' => 'smallint', 'default' => ':NULL')
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr=array())
	{
		return new pjRoomModel($attr);
	}
}
?>