<?php

class ExamController extends Zend_Controller_Action
{
	protected $examm;
	protected $examf;
	protected $examtoolbarf;
	protected $questionm;

	public function init()
	{
		$this->_alert = $this->_helper->getHelper("FlashMessenger");

		$this->examm = new Model_Exam();
        $this->examf = new Form_Exam();
        $this->examtoolbarf = new Form_ExamToolbar();
		$this->questionm = new Model_Question();
	}

	public function indexAction()
	{
		$this->_redirect('/exam/list/');
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->examf->isValid($_POST))
        	{
	            $result = $this->examm->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New exam added.', "status"=>"success"));
	                $this->_redirect("/exam/list/");
	            }
        	}
        }

		$this->view->form = $this->examf;
	}

	public function listAction()
	{
		$url_params = '';

		$course = $this->_request->getParam('course', null);
    	$search = $this->_request->getParam('search', null);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'page'		=> $page,
    		'condition'	=> array()
		);

    	if($search != null)
    	{
    		$url_params .= '/search/'.$search;
    		$params['condition'][] = "`exam_name` LIKE '%".$search."%'";
    		$this->examtoolbarf->search->setValue($search);
    	}

        if($course != null && $course != 0)
        {
            $url_params .= '/course/'.$course;
            $params['condition'][] = "`course_id` = ".$course;
            $this->examtoolbarf->course->setValue($course);
        }
        
		if($this->_request->isPost())
    		$this->_redirect('/exam/list'.$url_params.'/page/'.$page);

    	$this->view->data = $this->examm->paginate($params);
    	$this->view->form = $this->examtoolbarf;
	}

	public function deleteAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$exam = $this->examm->find($id)->current();

			if($exam)
			{
				$exam_id = $exam->exam_id;
				$exam->delete();

				// Delete question under exam
				$this->questionm->delete('exam_id = '.$exam_id);

				
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> Exam deleted.', "status"=>"success"));
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect exam ID.', "status"=>"error"));
		}
		$this->_redirect('/exam/list/');
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$exam = $this->examm->find($id)->current();
			
			if($exam)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->examf->isValid($_POST))
		        	{
			            $result = $this->examm->edit($this->_request->getPost(), $exam->exam_id);
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i>  Exam updated.', "status"=>"success"));
			                $this->_redirect("/exam/edit/id/".$exam->exam_id);
			            }
		        	}
		        }
				
				$this->examf->populate($exam->toArray());
				$this->view->form = $this->examf;
				$this->view->exam = $exam;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid exam ID.', "status"=>"error"));
				$this->_redirect('/exam/list/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect exam ID.', "status"=>"error"));
			$this->_redirect('/exam/list/');
		}
	}
}