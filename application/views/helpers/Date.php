<?php
class Zend_View_Helper_Date
{
	public function Date($mysql_datetime, $format = null)
	{
		$phpdatetime = strtotime($mysql_datetime);
		if($format == null)
			$format = 'd M Y h:i A';
		return date($format, $phpdatetime);
	}
}