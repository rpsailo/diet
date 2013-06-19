<?php

class ProgramController extends Zend_Controller_Action
{
	protected $auth;

	protected $programmodel;
	protected $programform;
	protected $programtoolbarform;

	public function init()
	{
		$this->programmodel = new Model_Program();
		$this->programform = new Form_Program();
		$this->programtoolbarform = new Form_ProgramToolbar();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		$url_params = '';

    	$search = $this->_request->getParam('search', null);
    	$limit = $this->_request->getParam('limit', 20);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'limit' 	=> $limit,
    		'page'		=> $page,
    		'order'		=> 'name asc',
    		'condition'	=> array()
		);

		$this->programtoolbarform->limit->setValue($limit);

    	if($search != null)
    	{
    		$url_params .= '/search/'.$search;
    		$params['condition'][] = "`name` LIKE '%".$search."%'";
    		$this->programtoolbarform->search->setValue($search);
    	}

		if($this->_request->isPost())
    		$this->_redirect('/program/index'.$url_params.'/page/'.$page.'/limit/'.$limit);

    	$this->view->data = $this->programmodel->paginate($params);
    	$this->view->form = $this->programtoolbarform;
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->programform->isValid($_POST))
        	{
	            $result = $this->programmodel->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New programme added.', "status"=>"success"));
	                $this->_redirect("/program/");
	            }
        	}
        }

		$this->view->form = $this->programform;
	}

	public function removeAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$program = $this->programmodel->find($id)->current();

			if($program)
			{
				$programname = $program->name;
				$program->delete();
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> Programme "'.$programname.'" deleted.', "status"=>"success"));
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect programme ID.', "status"=>"error"));
		}

		$this->_redirect('/program/');
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$program = $this->programmodel->find($id)->current();
			
			if($program)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->programform->isValid($_POST))
		        	{
			            $result = $this->programmodel->edit($this->_request->getPost(), $program->id);
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Programme "'.$program->name.'" updated.', "status"=>"success"));
			                $this->_redirect("/program/");
			            }
		        	}
		        }
				
				$this->programform->populate($program->toArray());
				$this->programform->faculties->setValue(explode(",", $program->faculties));
				$this->view->form = $this->programform;
				$this->view->program = $program;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid programme ID.', "status"=>"error"));
				$this->_redirect('/program/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect programme ID.', "status"=>"error"));
			$this->_redirect('/program/');
		}
	}

	public function viewAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$program = $this->programmodel->find($id)->current();
			if($program)
			{
				$this->view->program = $program;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid programme ID.', "status"=>"error"));
				$this->_redirect('/program/');
			}	
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect programme ID.', "status"=>"error"));
			$this->_redirect('/program/');
		}
	}
}



