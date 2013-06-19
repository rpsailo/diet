<?php

class SchoolController extends Zend_Controller_Action
{
	protected $auth;

	protected $schoolmodel;
	protected $schoolform;
	protected $schooltoolbarform;

	public function init()
	{
		$this->schoolmodel = new Model_School();
		$this->schoolform = new Form_School();
		$this->schooltoolbarform = new Form_SchoolToolbar();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		$url_params = '';

    	$search = $this->_request->getParam('search', null);
    	$year_of_establishment = $this->_request->getParam('year_of_establishment', null);
    	$type = $this->_request->getParam('type', null);
    	$limit = $this->_request->getParam('limit', 20);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'limit' 	=> $limit,
    		'page'		=> $page,
    		'order'		=> 'name asc',
    		'condition'	=> array()
		);

		$this->schooltoolbarform->limit->setValue($limit);

    	if($search != null)
    	{
    		$url_params .= '/search/'.$search;
    		$params['condition'][] = "`name` LIKE '%".$search."%'";
    		$this->schooltoolbarform->search->setValue($search);
    	}
    	if($year_of_establishment != null)
    	{
    		$url_params .= '/year_of_establishment/'.$year_of_establishment;
    		$params['condition'][] = "`year_of_establishment` = ".$year_of_establishment;
    		$this->schooltoolbarform->year_of_establishment->setValue($year_of_establishment);
    	}
    	if($type != null)
    	{
    		$url_params .= '/type/'.$type;
    		$params['condition'][] = "`type` = '".$type."'";
    		$this->schooltoolbarform->type->setValue($type);
    	}

		if($this->_request->isPost())
    		$this->_redirect('/school/index'.$url_params.'/page/'.$page.'/limit/'.$limit);

    	$this->view->data = $this->schoolmodel->paginate($params);
    	$this->view->form = $this->schooltoolbarform;
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->schoolform->isValid($_POST))
        	{
	            $result = $this->schoolmodel->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New school added.', "status"=>"success"));
	                $this->_redirect("/school/");
	            }
        	}
        }

		$this->view->form = $this->schoolform;
	}

	public function removeAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$school = $this->schoolmodel->find($id)->current();

			if($school)
			{
				$schoolname = $school->name;
				$school->delete();
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> "'.$schoolname.'" deleted.', "status"=>"success"));
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect school ID.', "status"=>"error"));
		}
		$this->_redirect('/school/');
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$school = $this->schoolmodel->find($id)->current();
			
			if($school)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->schoolform->isValid($_POST))
		        	{
			            $result = $this->schoolmodel->edit($this->_request->getPost(), $school->id);
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> "'.$school->name.'" updated.', "status"=>"success"));
			                $this->_redirect("/school");
			            }
		        	}
		        }
				
				$this->schoolform->populate($school->toArray());
				$this->view->form = $this->schoolform;
				$this->view->school = $school;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid school ID.', "status"=>"error"));
				$this->_redirect('/school/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect school ID.', "status"=>"error"));
			$this->_redirect('/school/');
		}
	}
}



