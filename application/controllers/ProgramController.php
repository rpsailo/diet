<?php

class ProgramController extends Zend_Controller_Action
{
	protected $auth;

	protected $programmodel;
	protected $programform;
	protected $programtoolbarform;
	protected $trainingmodel;
	protected $traineeform;

	public function init()
	{
		$this->programmodel = new Model_Program();
		$this->programform = new Form_Program();
		$this->programtoolbarform = new Form_ProgramToolbar();
		$this->trainingmodel = new Model_Training();
		$this->traineeform = new Form_Trainee();

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

	public function traineeAction()
	{
		$id = $this->_request->getParam('id', null); // Program ID
		$page = $this->_request->getParam('page', 1);
		
		if($id)
		{
			$program = $this->programmodel->find($id)->current();
			
			if($program)
			{
				$params = array(
		    		'page'		=> $page,
		    		'order'		=> 'teacher_name asc',
		    		'condition'	=> array('program_id = '.$id),
		    		'table'			=> 'training',
					'field'			=> 'training.*',
					'join_table'	=> 'teacher',
		    		'join_on'		=> 'training.teacher_id = teacher.id',
		    		'join_field'	=> array('teacher_name'=>'teacher.name', 'school_id')
				);
				$this->view->data = $this->trainingmodel->paginate($params);
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

	public function addTraineeAction()
	{
		$id = $this->_request->getParam('id'); // Program ID

		if($id)
		{
			$program = $this->programmodel->find($id)->current();
			
			if($program)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->traineeform->isValid($_POST))
		        	{
			            $result = $this->trainingmodel->create($this->_request->getPost());
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New trainee added for "'.$program->name.'".', "status"=>"success"));
			                $this->_redirect("/program/trainee/id/".$program->id);
			            }
		        	}
		        }
				
				$this->traineeform->program_id->setValue($program->id);
				$this->traineeform->from->setValue(date('Y-m-d',strtotime('today')));
				$this->traineeform->to->setValue(date('Y-m-d',strtotime('tomorrow')));
				$this->traineeform->status->setValue('Active');
				$this->view->form = $this->traineeform;
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

	public function removeTrainingAction()
	{
		$id = $this->_request->getParam('id'); // Training ID

		if($id)
		{
			$training = $this->trainingmodel->find($id)->current();
			
			if($training)
			{
				$teacher_id = $training->teacher_id;
				$training->delete();
                $this->_redirect("/teacher/training/id/".$teacher_id);
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid training ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect training ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}
}



