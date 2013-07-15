<?php

class SchoolStatisticController extends Zend_Controller_Action
{
	protected $auth;

	protected $schoolmodel;
	protected $schoolform;
	protected $schooltoolbarform;
	protected $schoolstatistictoolbarform;

	public function init()
	{
		$this->schoolmodel = new Model_School();
		$this->schoolstatisticmodel = new Model_SchoolStatistic();
		$this->schoolform = new Form_School();
		$this->schoolstatisticform = new Form_SchoolStatistic();
		$this->schooltoolbarform = new Form_SchoolToolbar();
		$this->statistictoolbarform = new Form_StatisticToolbar();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		$url_params = '';

    	$name = $this->_request->getParam('name', null);
    	$level = $this->_request->getParam('level', null);
    	$type = $this->_request->getParam('type', null);
    	$year = $this->_request->getParam('year', null);
    	$limit = $this->_request->getParam('limit', 20);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'limit' 	=> $limit,
    		'page'		=> $page,
    		'field'		=> 'school_statistic.*',
    		'join_table'=> 'school',
    		'join_on'	=> 'school.id = school_statistic.school_id',
    		'join_field'=> array('name'),
    		'order'		=> array('school_statistic.year DESC','school.name desc'),
    		'condition'	=> array()
		);

		$this->statistictoolbarform->setAction('/school-statistic/');
		$this->statistictoolbarform->new->setAttrib('onclick',"window.location='/school-statistic/add/'");
		$this->statistictoolbarform->limit->setValue($limit);

    	if($year != null)
    	{
    		$url_params .= '/year/'.$year;
    		$params['condition'][] = "`year` = ".$year;
    		$this->statistictoolbarform->year->setValue($year);
    	}

    	if($name != null)
    	{
    		$url_params .= '/name/'.$name;
    		$params['condition'][] = "`name` LIKE '%".$name."%'";
    		$this->statistictoolbarform->name->setValue($name);
    	}

    	if($level != null)
    	{
    		$url_params .= '/level/'.$level;
    		$params['condition'][] = "`level` = '".$level."'";
    		$this->statistictoolbarform->level->setValue($level);
    	}

    	if($type != null)
    	{
    		$url_params .= '/type/'.$type;
    		$params['condition'][] = "`type` = '".$type."'";
    		$this->statistictoolbarform->type->setValue($type);
    	}

		if($this->_request->isPost())
    		$this->_redirect('/school-statistic/index'.$url_params.'/page/'.$page.'/limit/'.$limit);

    	$this->view->data = $this->schoolstatisticmodel->paginate($params);
    	$this->view->form = $this->statistictoolbarform;
	}

	public function removeAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$schoolstatistic = $this->schoolstatisticmodel->find($id)->current();
			$school = $this->schoolmodel->find($schoolstatistic->school_id)->current();

			if($schoolstatistic)
			{
				$schoolname = $school->name;
				$schoolstatistic->delete();
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> School statistic for "'.$schoolname.'" deleted.', "status"=>"success"));
				$this->_redirect('/school-statistic/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect statistic ID.', "status"=>"error"));
			$this->_redirect('/school-statistic/');
		}
	}

	public function addAction()
	{
		if($this->_request->isPost())
        {
        	if($this->schoolstatisticform->isValid($_POST))
        	{
        		$form_data = $this->_request->getPost();

            	$result = $this->schoolstatisticmodel->create($form_data, $form_data['school_level']);
        	
	            if($result)
	            {
	            	$school = $this->schoolmodel->find($form_data['school_id'])->current();
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New statistic for "'.$school->name.'" added.', "status"=>"success"));
	                $this->_redirect("/school-statistic/");
	            }
        	}
        }

    	$this->view->form = $this->schoolstatisticform;
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$schoolstatistic = $this->schoolstatisticmodel->find($id)->current();
			$school = $this->schoolmodel->find($schoolstatistic->school_id)->current();
			
			if($schoolstatistic)
			{
				if($this->_request->isPost())
		        {
		        	if($this->schoolstatisticform->isValid($_POST))
		        	{
		        		$form_data = $this->_request->getPost();
		        		$form_data['id'] = $id;

		            	$result = $this->schoolstatisticmodel->edit($form_data, $form_data['school_level']);
			            
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Statistic updated for "'.$school->name.'".', "status"=>"success"));
			                $this->_redirect("/school-statistic/");
			            }
		        	}
		        }

		        $this->schoolstatisticform->populate($schoolstatistic->toArray());
		        $this->schoolstatisticform->school_level->setValue($school->level);
		        $this->schoolstatisticform->cancel->setAttrib('onclick', "window.location = '/school-statistic/'");
		    	$this->view->form = $this->schoolstatisticform;
		    	$this->view->school = $school;
		    }
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid statistic ID.', "status"=>"error"));
				$this->_redirect('/school-statistic/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect statistic ID.', "status"=>"error"));
			$this->_redirect('/school-statistic/');
		}
	}

	public function viewAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$schoolstatistic = $this->schoolstatisticmodel->find($id)->current();
			$school = $this->schoolmodel->find($schoolstatistic->school_id)->current();
			
			if($schoolstatistic)
			{
		    	$this->view->statistic = $schoolstatistic;
		    	$this->view->school = $school;
		    }
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid statistic ID.', "status"=>"error"));
				$this->_redirect('/school-statistic/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect statistic ID.', "status"=>"error"));
			$this->_redirect('/school-statistic/');
		}
	}

}

