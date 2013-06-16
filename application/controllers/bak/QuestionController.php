<?php

class QuestionController extends Zend_Controller_Action
{
    protected $questionm;
    protected $questionf;
    protected $questiontoolbarf;

    public function init()
    {
        $this->_alert = $this->_helper->getHelper("FlashMessenger");

        $this->questionm = new Model_Question();
        $this->questionf = new Form_Question();
        $this->questiontoolbarf = new Form_QuestionToolbar();
    }
    
    public function indexAction()
    {
        $this->_redirect('/question/list/');
    }

    public function addAction()
    {
        if(isset($_SESSION['current_exam']))
        {
            $current_exam = $_SESSION['current_exam'];
            if($current_exam != null)
                $this->questionf->exam_id->setValue($current_exam);
        }
        else $_SESSION['current_exam'] = null;

        if($this->_request->isPost())
        {
            if($this->questionf->isValid($_POST))
            {
                $result = $this->questionm->create($this->_request->getPost());
                if($result)
                {
                    if(isset($_SESSION['current_exam']))
                        $_SESSION['current_exam'] = $this->questionf->exam_id->getValue();

                    $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New question added. Add more', "status"=>"success"));
                    $this->_redirect("/question/add/");
                }
            }
        }

        $this->view->form = $this->questionf;
    }
    
    public function listAction()
    {
        $url_params = '';

        $course = $this->_request->getParam('course', null);
        $search = $this->_request->getParam('search', null);
        $page = $this->_request->getParam('page', 1);

        $params = array(
            'page'      => $page,
            'condition' => array(),
            'field'         => array('question.*'),
            'join_table'    => 'exam',
            'join_on'       => 'question.exam_id = exam.exam_id',
            'join_field'    => array('exam_name', 'start_at', 'course_id')
        );

        if($search != null)
        {
            $url_params .= '/search/'.$search;
            $params['condition'][] = "`question` LIKE '%".$search."%'";
            $this->questiontoolbarf->search->setValue($search);
        }
        if($course != null && $course != 0)
        {
            $url_params .= '/course/'.$course;
            $params['condition'][] = "`course_id` = ".$course;
            $this->questiontoolbarf->course->setValue($course);
        }

        if($this->_request->isPost())
            $this->_redirect('/question/list'.$url_params.'/page/'.$page);

        $this->view->data = $this->questionm->paginate($params);
        $this->view->form = $this->questiontoolbarf;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');

        if($id)
        {
            $question = $this->questionm->find($id)->current();

            if($question)
            {
                $question->delete();

                $this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> Question deleted.', "status"=>"success"));
            }
        }
        else
        {
            $this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect question ID.', "status"=>"error"));
        }
        $this->_redirect('/question/list/');
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');

        if($id)
        {
            $question = $this->questionm->find($id)->current();
            
            if($question)
            {
                if($this->_request->isPost())
                {
                    if($this->questionf->isValid($_POST))
                    {
                        $result = $this->questionm->edit($this->_request->getPost(), $question->question_id);
                        if($result)
                        {
                            $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i>  Question updated.', "status"=>"success"));
                            $this->_redirect("/question/edit/id/".$question->question_id);
                        }
                    }
                }
                
                $this->questionf->populate($question->toArray());
                $this->view->form = $this->questionf;
                $this->view->question = $question;
            }
            else
            {
                $this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid question ID.', "status"=>"error"));
                $this->_redirect('/question/list/');
            }
        }
        else
        {
            $this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect question ID.', "status"=>"error"));
            $this->_redirect('/question/list/');
        }
    }
}



