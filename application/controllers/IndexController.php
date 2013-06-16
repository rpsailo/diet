<?php

class IndexController extends Zend_Controller_Action
{
	protected $auth;

	protected $userm;

	public function init()
	{
		$this->userm = new Model_User();

		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		if($this->auth->hasIdentity())
			$current_user = $this->auth->getIdentity();
	}
}



