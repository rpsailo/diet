<?php
class ResultController extends Zend_Controller_Action
{
    protected $auth;

    protected $userm;
    protected $coursem;
    protected $examm;
    protected $studentm;
    protected $questionm;
    protected $examsessionm;
    protected $facultym;

    public function init()
    {
        $this->coursem = new Model_Course();
        $this->userm = new Model_User();
        $this->examm = new Model_Exam();
        $this->studentm = new Model_Student();
        $this->questionm = new Model_Question();
        $this->examsessionm = new Model_ExamSession();
        $this->facultym = new Model_Faculty();

        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        if($this->auth->hasIdentity())
        {
            $current_user = $this->auth->getIdentity();
            if($current_user->type == 'student')
            {
                $exam = $this->_request->getParam('exam', null);

                $student = $this->studentm->fetchRow('user_id='.$current_user->id);
                $exams_completed = $this->examm->completed($student->student_id, null);
                if($exam == null)
                {
                    foreach($exams_completed as $e)
                    {
                        $exam = $e->exam_id;
                        break;
                    }
                }

                $this->view->exam = $exam;
                $this->view->examm = $this->examm;
                $this->view->questionm = $this->questionm;
                $this->view->student = $student;
                $this->view->exams_completed = $exams_completed;
                $this->view->current_exam = $this->examm->completed($student->student_id, $exam);
                
                $this->renderScript('result/student.phtml');
            }
            else if($current_user->type == 'administrator' || $current_user->type == 'faculty')
            {
                $exam = $this->_request->getParam('exam', 0);
                $course = $this->_request->getParam('course', 0);

                $this->view->courses = $this->coursem->fetchAll();

                if($exam != 0 && $course != 0)
                {
                    $this->view->students = $this->studentm->fetchAll('course_id = '.$course);
                }
                if($course != 0)
                    $this->view->exams = $this->examm->fetchAll('course_id = '.$course);

                $this->view->facultym = $this->facultym;
                $this->view->examsessionm = $this->examsessionm;
                $this->view->questionm = $this->questionm;
                $this->view->exam = $this->examm->find($exam)->current();
                $this->view->course = $this->coursem->find($course)->current();
                $this->renderScript('result/admin.phtml');
            }
            else
            {
                $this->_redirect('/');
            }
        }
    }
}