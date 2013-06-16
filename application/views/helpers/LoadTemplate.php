<?php
class Zend_View_Helper_LoadTemplate extends Zend_View_Helper_Abstract
{
	public function LoadTemplate($zone, $template)
	{
		$data = new Zend_Config_Xml('./templates/' . $zone . '/' . $template . '/template.xml');
		
		$global_styles = $data->globalstyles->stylesheet->toArray();
		$styles = $data->stylesheets->stylesheet->toArray();

		$global_scripts = (!is_string($data->globalscripts->script))? $data->globalscripts->script->toArray() : $data->globalscripts->script;
		$scripts = (!is_string($data->scripts->script))? $data->scripts->script->toArray() : $data->scripts->script;
		
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();

		$this->view->pageId = $zone . "_" . preg_replace("/\s/", "_", $controller);

		// Load controller + actions scripts
		if(file_exists(SITE_PATH . '/templates/' . $zone . '/' . $template . '/js/' . $controller . '.js'))
			$this->view->headScript()->prependFile('/templates/' . $zone . '/' . $template . '/js/' . $controller . '.js');
		if(file_exists(SITE_PATH . '/templates/' . $zone . '/' . $template . '/js/' . $controller . '-' . $action . '.js'))
			$this->view->headScript()->prependFile('/templates/' . $zone . '/' . $template . '/js/' . $controller . '-' . $action . '.js');

		// Load global styles
		if(is_array($global_styles))
		{
			if(!array_key_exists('file', $global_styles))
			{
				foreach($global_styles as $s)
				{
					if($s['file'] != "")
					{
						if($s['media'] != "")
							$this->view->headLink()->appendStylesheet($s['file'], $s['media']);
						else
							$this->view->headLink()->appendStylesheet($s['file']);
					}
				}
			}
			else 
			{
				$s = $global_styles;
				if($s['file'] != "") 
				{
					if($s['media'] != "")
						$this->view->headLink()->appendStylesheet($s['file'], $s['media']);
					else
						$this->view->headLink()->appendStylesheet($s['file']);
				}
			}
		}
		
		// Load template styles
		if(is_array($styles))
		{
			if(!array_key_exists('file', $styles))
			{
				foreach($styles as $s)
				{
					if($s['media'] != null)
						$this->view->headLink()->appendStylesheet('/templates/' . $zone . '/' . $template . '/' . $s['file'], $s['media']);
					else
						$this->view->headLink()->appendStylesheet('/templates/' . $zone . '/' . $template . '/' . $s['file']);
				}
			}
			else
			{
				$s = $styles;
				if($s['media'] != null)
					$this->view->headLink()->appendStylesheet('/templates/' . $zone . '/' . $template . '/' . $s['file'], $s['media']);
				else
					$this->view->headLink()->appendStylesheet('/templates/' . $zone . '/' . $template . '/' . $s['file']);
			}
		}
		
		// Load controller + actions styles
		if(file_exists(SITE_PATH . '/templates/' . $zone . '/' . $template . '/css/' . $controller . '.css'))
			$this->view->headLink()->appendStylesheet('/templates/' . $zone . '/' . $template . '/css/' . $controller . '.css');
		if(file_exists(SITE_PATH . '/templates/' . $zone . '/' . $template . '/css/' . $controller . '-' . $action . '.css'))
			$this->view->headLink()->appendStylesheet('/templates/' . $zone . '/' . $template . '/css/' . $controller . '-' . $action .'.css');
		
		
		// Load template scripts
		if(is_array($scripts))
		{
			foreach($scripts as $js)
			{
				if($js != "")
					$this->view->headScript()->prependFile('/templates/' . $zone . '/' . $template . '/' . $js);
			}
		}
		else
		{
			$js = $scripts;
			if($js != "")
				$this->view->headScript()->prependFile('/templates/' . $zone . '/' . $template . '/' . $js);
		}
		
		// Load global scripts
		if(is_array($global_scripts))
		{
			$global_scripts = array_reverse($global_scripts);
			foreach($global_scripts as $js)
			{
				if($js != "")
					$this->view->headScript()->prependFile($js);
			}
		}
		else
		{
			$js = $global_scripts;
			if($js != "")
				$this->view->headScript()->prependFile($js);
		}
	}
}