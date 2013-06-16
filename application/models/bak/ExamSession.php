<?php 
class Model_ExamSession extends System_DbTable
{
	protected $_name='examsession';
	protected $auth;
	protected $studentm;
	protected $examm;
	protected $questionm;
	protected $current_user;

	public function init()
	{
		$this->auth = Zend_Auth::getInstance();
		$this->studentm = new Model_Student();
		$this->examm = new Model_Exam();
		$this->questionm = new Model_Question();

		if($this->auth->hasIdentity())
			$this->current_user = $this->auth->getIdentity();
	}
	
	public function create($data)
	{
		$new_row = $this->createRow();
        $new_row->student_id = $data['student_id'];
        $new_row->exam_id = $data['exam_id'];
        $new_row->course_id = $data['course_id'];
        $new_row->questions = $data['questions'];
        $new_row->current_question = $data['current_question'];
        $new_row->time_elapsed = 0; // In seconds
        $new_row->status = 'started'; // Started and Completed
       	$new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
	}

	public function questions($session_id = null)
	{
		if($session_id == null)
			$session_id = $this->get_session();
		if($session_id)
		{
			$select = $this->select();
			$select->where('id = '.$session_id);
			$session = $this->fetchRow($select);

			if(strlen($session->questions))
				return unserialize($session->questions);
			else
				return null;
		}
		else return null;
	}

	public function question($question_no, $session_id = null)
	{
		if($session_id == null)
			$session_id = $this->get_session();

		if($session_id)
		{
			$select = $this->select();
			$select->where('id = '.$session_id);
			$session = $this->fetchRow($select);

			if(strlen($session->questions))
			{
				$questions = unserialize($session->questions);
				if(isset($questions[$question_no - 1]))
				{
					// Update current question no.
					$session->current_question = $question_no;
					$session->save();

					return $this->questionm->get($questions[$question_no - 1]['q']);
				}
			}
			else
				return null;
		}
		else return null;
	}

	public function current_question($session_id = null)
	{
		if($session_id == null)
			$session_id = $this->get_session();

		if($session_id)
		{
			$select = $this->select();
			$select->where('id = '.$session_id);
			$session = $this->fetchRow($select);

			if(strlen($session->questions))
			{
				$questions = unserialize($session->questions);
				if(isset($questions[$session->current_question - 1]))
				{
					$return = array(
						'question_no' => $session->current_question,
						'question' => $this->questionm->get($questions[$session->current_question - 1]['q'])
						);
					return $return;
				}
			}
			else
				return null;
		}
		else return null;
	}

	/*
	** @param $questions: array in the form array(0=>array('q'=>1, 'a'=>'opt1'))
	*/
	public function update_questions($questions = array())
	{
		$session = $this->find($this->get_session())->current();
		$session->questions = serialize($questions);
		return $session->save();
	}

	public function current($session_id)
	{
		if($session_id == null)
			$session_id = $this->get_session();

		if($session_id)
		{
			$select = $this->select();
			$select->where('id = '.$session_id);
			$session = $this->fetchRow($select);

			if($session)
				return $session->current_question;
			else
				return null;
		}
		else return null;
	}

	public function prepare()
	{
		if($this->auth->hasIdentity())
		{
			$current_student = $this->studentm->fetchRow('user_id='.$this->current_user->id);
			$active_exam = $this->examm->exam_today($current_student->course_id);
			$exam_questions = $this->questionm->pick_random($active_exam->exam_id);
			$first_question = 1;
			$exam_session_data = array(
				'student_id'		=> $current_student->student_id,
				'exam_id'			=> $active_exam->exam_id,
				'course_id'			=> $active_exam->course_id,
				'questions'			=> serialize($exam_questions),
				'current_question'	=> $first_question,
				'exam_duration'		=> $active_exam->exam_duration
			);

			$session_id = $this->create($exam_session_data);
			$_SESSION['exam_session_id'] = $session_id;

			return $session_id;
		}
		else return false;
	}

	public function start($session_id, $exam_duration = 0)
	{
		if($session_id)
		{
			$now = date('Y-m-d H:i:s');
			$row = $this->find($session_id)->current();
			$row->start_at = $now;
    		$row->end_at = new Zend_Db_Expr('DATE_ADD("'.$now.'", INTERVAL '.$exam_duration.' MINUTE)');
    		$row->save();
		}
		else return false;
	}

	public function check($student_id, $exam_id, $exam_start_at, $duration)
	{
		$select = $this->select();
		$select->where('student_id = '.$student_id);
		$select->where('exam_id = '.$exam_id);
		$select->where(new Zend_Db_Expr('DATE(`start_at`) = DATE("'.$exam_start_at.'")'));
		$select->where(new Zend_Db_Expr('`end_at` > "'.date('Y-m-d H:i:s').'"'));
		return $this->fetchRow($select);
	}

	public function get_session()
	{
		if(isset($_SESSION['exam_session_id']))
			return $_SESSION['exam_session_id'];
		else return null;
	}

	public function complete($session_id = null)
	{
		if($session_id == null)
			$session_id = $this->get_session();

		if($session_id)
		{
			$row = $this->find($session_id)->current();
			
			$exam_id = $row->exam_id;
			$exam = $this->examm->find($exam_id)->current();
	    	$now = new DateTime('now');
			$exam_end_at = new DateTime($row->end_at);
			if($now >= $exam_end_at)
			{
				$this->examm->update(array(
					'status' => 'completed'
					), '`exam_id` = '.$row->exam_id);
			}

			$row->status = 'completed';
			return $row->save();
		}
		else return null;
	}

	public function exam_taken_today($student_id)
	{
		$select = $this->select();
		$select->where('student_id = '.$student_id);
		$select->where('status = "completed"');
		// $select->where('exam_id = '.$exam_id);
		$select->where(new Zend_Db_Expr('DATE(`start_at`) = "'.date('Y-m-d').'"'));
		return $this->fetchRow($select);
	}

}