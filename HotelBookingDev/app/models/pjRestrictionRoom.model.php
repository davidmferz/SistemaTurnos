<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjRestrictionRoomModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'restrictions_rooms';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'restriction_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'room_number_id', 'type' => 'int', 'default' => ':NULL'),
	);
	
	public static function factory($attr=array())
	{
		return new pjRestrictionRoomModel($attr);
	}
}
?>