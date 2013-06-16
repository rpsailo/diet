<?php
class Zend_View_Helper_Setting
{
	public function Setting($key = null)
	{
		if($key != null)
		{
			$setting_model = new Model_Setting();
			$setting = $setting_model->get_data($key);

			if($setting)
				return $setting;
		}
		else return null;
	}
}