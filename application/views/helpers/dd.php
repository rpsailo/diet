<?php
class Zend_View_Helper_dd
{
	public function dd($data)
	{
		echo '<pre style="background:#eeeeee;border:1px solid #aaaaaaa;padding:10px 20px;margin:30px;">';
		var_dump($data);
		echo '</pre>';
		exit;
	}
}