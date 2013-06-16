<?php
class System_ModuleConfig extends Zend_Controller_Plugin_Abstract
{
	/*
	 * Setup Module Specific Configuration
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) 
	{
		$module = $request->getModuleName();
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		
		// if($module != "default")
		// {
		// 	$layout->setLayout($module);
		// 	$view->zone = $module;
		// }
		
		$view->moduleName = $module;
		$view->controllerName = $request->getControllerName();
		$view->actionName = $request->getActionName();
		$templatePath = "/templates/" . $view->zone . "/" . $view->template;
		$view->templatePath = $templatePath;
		$view->messages = "";
		Zend_Registry::set("templatePath", $templatePath);
	}
}