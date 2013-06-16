<?php
class System_Acl extends Zend_Controller_Plugin_Abstract
{
	protected $user_access_level;
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		$acl = Zend_Registry::get('acl');

		$auth = Zend_Auth::getInstance();
		
		$this->user_access_level = 'public';
		if($auth->hasIdentity())
		{
			$identity = $auth->getIdentity(); 
			$this->user_access_level = strtolower($identity->role); 
		}
		 
		$module = $request->getModuleName(); 
		$controller = $request->getControllerName(); 
		$action = $request->getActionName(); 
		$resource = $module . ":" . $controller . ":" . $action;
		
		$resources = $acl->getResources();

		if(in_array($resource, $resources))
		{
			if (!$acl->isAllowed($this->user_access_level, $resource, $action))
			{
				$this->denyAccess($request);
			}
		}
		else
		{
			$this->denyAccess($request);
		}
	}
	
	protected function denyAccess($request)
	{
		if ($this->user_access_level == 'public')
		{ 
			$request->setModuleName('default'); 
			$request->setControllerName('auth'); 
			$request->setActionName('login');
		}
		else
		{ 
			$request->setControllerName('error'); 
		   	// $request->setActionName('error'); 
		   	$request->setActionName('noauth'); 
	   	}
	}
}
?>