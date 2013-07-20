<?php

class SubDivisionController extends Zend_Controller_Action
{
	protected $auth;

	protected $schoolmodel;
	protected $schoolform;
	protected $schooltoolbarform;
	protected $schoolstatistictoolbarform;

	public function init()
	{
		$this->subdivisionblockmodel = new Model_SubDivisionBlock();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function fetchByDistrictAction()
	{
		$district = $this->_request->getParam('district');

		$subdivions = $this->subdivisionblockmodel->getByDistrict($district);
		$subdivisiondata = array();
		foreach($subdivions as $s)
			$subdivisiondata[] = array('id'=>$s->id, 'name'=>$s->name);

		$this->_helper->json($subdivisiondata);
	}

}