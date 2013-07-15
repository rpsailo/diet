<?php

class SchoolController extends Zend_Controller_Action
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
		$this->schoolstatistictoolbarform = new Form_SchoolStatisticToolbar();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		$url_params = '';

    	$search = $this->_request->getParam('search', null);
    	$year_of_establishment = $this->_request->getParam('year_of_establishment', null);
    	$type = $this->_request->getParam('type', null);
    	$level = $this->_request->getParam('level', null);
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
    	if($level != null)
    	{
    		$url_params .= '/level/'.$level;
    		$params['condition'][] = "`level` = '".$level."'";
    		$this->schooltoolbarform->level->setValue($level);
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

	public function fetchByLevelAction()
	{
		$level = $this->_request->getParam('level');

		$schools = $this->schoolmodel->getByLevel($level);
		$schooldata = array();
		foreach($schools as $s)
			$schooldata[] = array('id'=>$s->id, 'name'=>$s->name);

		$this->_helper->json($schooldata);
	}

	public function statisticsAction()
    {
    	$id = $this->_request->getParam('id');

		if($id)
		{
			$school = $this->schoolmodel->find($id)->current();
			
			if($school)
			{
				$url_params = '';

		    	$year = $this->_request->getParam('year', null);
		    	$limit = $this->_request->getParam('limit', 20);
		    	$page = $this->_request->getParam('page', 1);

		    	$params = array(
		    		'limit' 	=> $limit,
		    		'page'		=> $page,
		    		'order'		=> 'year desc',
		    		'condition'	=> array(
		    			'school_id = '.$school->id
		    			)
				);

				$this->schoolstatistictoolbarform->setAction('/school/statistics/id/'.$school->id);
				$this->schoolstatistictoolbarform->new->setAttrib('onclick',"window.location='/school/new-statistic/id/".$school->id."'");
				$this->schoolstatistictoolbarform->limit->setValue($limit);

		    	if($year != null)
		    	{
		    		$url_params .= '/year/'.$year;
		    		$params['condition'][] = "`year` = ".$year;
		    		$this->schoolstatistictoolbarform->year->setValue($year);
		    	}

				if($this->_request->isPost())
		    		$this->_redirect('/school/statistics/id/'.$school->id.$url_params.'/page/'.$page.'/limit/'.$limit);

		    	$this->view->data = $this->schoolstatisticmodel->paginate($params);
		    	$this->view->form = $this->schoolstatistictoolbarform;
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

	public function newStatisticAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$school = $this->schoolmodel->find($id)->current();
			
			if($school)
			{
				$this->schoolstatisticform->removeElement('school_level');
				$this->schoolstatisticform->removeElement('school_id');
		        if($school->level == 'Primary School')
		        {
		        	$this->schoolstatisticform->removeElement('boys_8');
		        	$this->schoolstatisticform->removeElement('boys_7');	
		        	$this->schoolstatisticform->removeElement('boys_6');	
		        	$this->schoolstatisticform->removeElement('boys_5');	
		        	$this->schoolstatisticform->removeElement('girls_8');	
		        	$this->schoolstatisticform->removeElement('girls_7');	
		        	$this->schoolstatisticform->removeElement('girls_6');	
		        	$this->schoolstatisticform->removeElement('girls_5');	
		        }
	        	else if($school->level == 'Middle School')
	        	{
	        		$this->schoolstatisticform->removeElement('boys_1');
	        		$this->schoolstatisticform->removeElement('boys_2');
	        		$this->schoolstatisticform->removeElement('boys_3');
	        		$this->schoolstatisticform->removeElement('boys_4');
	        		$this->schoolstatisticform->removeElement('girls_1');
	        		$this->schoolstatisticform->removeElement('girls_2');
	        		$this->schoolstatisticform->removeElement('girls_3');
	        		$this->schoolstatisticform->removeElement('girls_4');
	        	}

				if($this->_request->isPost())
		        {
		        	if($this->schoolstatisticform->isValid($_POST))
		        	{
		        		$form_data = $this->_request->getPost();
		        		$form_data['school_id'] = $school->id;
		        		
		        		if($school->level == 'Primary School')
			            	$result = $this->schoolstatisticmodel->create($form_data, 'Primary School');
			        	else if($school->level == 'Middle School')
			            	$result = $this->schoolstatisticmodel->create($form_data, 'Middle School');
			            
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New statistic for "'.$school->name.'" added.', "status"=>"success"));
			                $this->_redirect("/school/statistics/id/".$school->id);
			            }
		        	}
		        }

		    	$this->view->form = $this->schoolstatisticform;
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

	public function editStatisticAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$schoolstatistic = $this->schoolstatisticmodel->find($id)->current();
			$school = $this->schoolmodel->find($schoolstatistic->school_id)->current();
			
			if($school)
			{
		        if($school->level == 'Primary School')
		        {
		        	$this->schoolstatisticform->removeElement('boys_8');	
		        	$this->schoolstatisticform->removeElement('boys_7');	
		        	$this->schoolstatisticform->removeElement('boys_6');	
		        	$this->schoolstatisticform->removeElement('boys_5');	
		        	$this->schoolstatisticform->removeElement('girls_8');	
		        	$this->schoolstatisticform->removeElement('girls_7');	
		        	$this->schoolstatisticform->removeElement('girls_6');	
		        	$this->schoolstatisticform->removeElement('girls_5');	
		        }
	        	else if($school->level == 'Middle School')
	        	{
	        		$this->schoolstatisticform->removeElement('boys_1');
	        		$this->schoolstatisticform->removeElement('boys_2');
	        		$this->schoolstatisticform->removeElement('boys_3');
	        		$this->schoolstatisticform->removeElement('boys_4');
	        		$this->schoolstatisticform->removeElement('girls_1');
	        		$this->schoolstatisticform->removeElement('girls_2');
	        		$this->schoolstatisticform->removeElement('girls_3');
	        		$this->schoolstatisticform->removeElement('girls_4');
	        	}

				if($this->_request->isPost())
		        {
		        	if($this->schoolstatisticform->isValid($_POST))
		        	{
		        		$form_data = $this->_request->getPost();
		        		$form_data['id'] = $id;
		        		
		        		if($school->level == 'Primary School')
			            	$result = $this->schoolstatisticmodel->edit($form_data, 'Primary School');
			        	else if($school->level == 'Middle School')
			            	$result = $this->schoolstatisticmodel->edit($form_data, 'Middle School');
			            
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Statistic updated for "'.$school->name.'".', "status"=>"success"));
			                $this->_redirect("/school/statistics/id/".$school->id);
			            }
		        	}
		        }

		        $this->schoolstatisticform->populate($schoolstatistic->toArray());
		        $this->schoolstatisticform->cancel->setAttrib('onclick', 'window.location = "/school/statistics/id/'.$school->id.'"');
		    	$this->view->form = $this->schoolstatisticform;
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

	public function removeStatisticAction()
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
				$this->_redirect('/school/statistics/id/'.$school->id);
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect statistic ID.', "status"=>"error"));
			$this->_redirect('/school/');
		}
	}

}

