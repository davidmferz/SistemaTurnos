<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjRestrictionModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'restrictions';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'restriction_type', 'type' => 'enum', 'default' => ':NULL'),
	);
	
	public static function factory($attr=array())
	{
		return new pjRestrictionModel($attr);
	}
}
?>