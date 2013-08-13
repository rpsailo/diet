<?php
/*
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/*
	 * Initialize Zend Locale
	 * @return Nothing
	 */
	public function _initLocale()
	{
		$locale = new Zend_Locale('en_IN');
		Zend_Registry::set('locale', $locale);
	}
	
	/*
	 * Initialize Zend Date And Set Registry 
	 * @return Nothing
	 */
	public function _initDateTime()
	{
		date_default_timezone_set('Asia/Kolkata');
		$date = new Zend_Date();
		Zend_Registry::set("timestamp", $date->get(Zend_Date::TIMESTAMP));
	}
	/*
	 * Load ModuleConfig Plugin To Initialize Module Bootstrap Configuration
	 * @return Nothing
	 */
	public function _initPlugins()
	{
		$this->bootstrap('frontController'); 
		$frontController = $this->getResource('frontController'); 
		$frontController->registerPlugin(new System_ModuleConfig());
		
		// ACL Instantiate
		$acl_helper = new System_AclHelper();
		$acl_helper->setRoles();
		$acl_helper->setResources();
		$acl_helper->setPrivileges();
		$acl_helper->setAcl();
		$frontController->registerPlugin(new System_Acl());
	}
	
	/*
	 * Initialize Configuration
	 * @return Zend_Config
	 */
	protected function _initConfig()
	{
		require APPLICATION_PATH. "/configs/db.php";
		$config = new Zend_Config($configuration);
		Zend_Registry::set('config', $config);
		return $config;
	}
	
	/*
	 * Initialize Database Connection
	 * @return Zend_Db
	 */
	protected function _initDb()
	{
		$this->bootstrap('config');
		$config = $this->getResource('config');
		$db = Zend_Db::factory($config->database);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		return $db;
	}
	
	/*
	 * Initialize Resource Autoloader
	 * @return Zend_Loader_Autoloader_Resource
	 */
	protected function _initAutoload()
	{	
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('System');
		$autoloader->registerNamespace('Twitter');
		$resourceloader = new Zend_Loader_Autoloader_Resource(array(
			'basePath'      => APPLICATION_PATH,
			'namespace'     => '',
			'resourceTypes' => array(
				'form' => array(
					'path'      => 'forms/',
					'namespace' => 'Form',
				),
				'model' => array(
					'path'      => 'models/',
					'namespace' => 'Model'
				),
			),
		));		
		return $autoloader;
	}
	
	/*
	 * Initialize Registry
	 * @return Nothing
	 */
	protected function _initRegistry()
	{
		$this->bootstrap('db');
		Zend_Registry::set("layout", "default");
		Zend_Registry::set("template", "default");
	}
	
	/*
	 * Initialize Router
	 * @return Nothing
	 */
	protected function _initRouter()
	{
		$this->bootstrap('FrontController');
		$this->bootstrap('db');
		$controller = $this->getResource('FrontController');
		$router = $controller->getRouter();
	}
	
	/*
	 * Initialize View
	 * @return Zend_View
	 */
	protected function _initView()
	{
		$this->bootstrap('db');
		
		$zoneName = 'default';
		$templateName = "default";
		$layoutName = "default";

		$auth = Zend_Auth::getInstance();
		
		Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/layouts/scripts',
									'layout' => $layoutName));
		$view = Zend_Layout::getMvcInstance()->getView();
		
		$view->layoutName = $layoutName.'.phtml';
		$view->zone = $zoneName;
		$view->template = $templateName;
		$templatePath = "/templates/" . $view->zone . "/" . $view->template;
		$view->templatePath = $templatePath;
		Zend_Registry::set("templatePath", $templatePath);
		
		$request = new Zend_Controller_Request_Http();
		$view->ajax = $request->isXmlHttpRequest();
		if($view->ajax)
		{
			$l = Zend_Layout::getMvcInstance();
			$l->disableLayout();
		}

		// Set authentication
		if($auth->hasIdentity())
		{
			$view->authenticate = true;
			$session_user = $auth->getIdentity();
			$view->user = $session_user; 
		}
		else
		{
			$view->authenticate = false;
		}
		$view->diet_district = "Serchhip";
		$view->doctype('XHTML1_TRANSITIONAL');
		$view->headTitle('DIET '.$view->diet_district.' Training Management System');
		
		$view->addHelperPath( dirname(__FILE__) . '/views/helpers/' );
		$view->addScriptPath( dirname(__FILE__) . '/views/scripts/' );
		
		/*$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
		$view->jQuery()->setVersion('1') // Get the latest jQuery version 1.x.x
			->setUiVersion('1') // Get the latest jQuery UI version 1.x.x
			->enable()
    		->uiEnable();*/
		Zend_Paginator::setDefaultScrollingStyle('Elastic');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('partials/navigation.phtml');


		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		
		$viewRenderer->view->addHelperPath(APPLICATION_PATH.'/views/helpers/');
		
		$viewRenderer->initView();
		$viewRenderer->setView($view);
		
		return $view;
    }
}
