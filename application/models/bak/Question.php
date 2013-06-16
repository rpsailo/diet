<?php 
 class Model_Question extends System_DbTable
{
	protected $_name='question';
	
	public function init()
	{
	}
	
	public function create($data)
	{
		$new_row = $this->createRow();
		$new_row->question_type = $data['question_type'];
        $new_row->question = $data['question'];
        $new_row->opt1 = $data['opt1'];
        $new_row->opt2= $data['opt2'];
        $new_row->opt3 = $data['opt3'];
        $new_row->opt4 = $data['opt4'];
		$new_row->ans = $data['ans'];
		$new_row->exam_id = $data['exam_id'];
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');

        return $new_row->save();
	}
	
	public function edit($data, $id)
	{
		if(is_numeric($id))
		{
			$row = $this->find($id)->current();
			$row->question_type = $data['question_type'];
	        $row->question = $data['question'];
	        $row->opt1 = $data['opt1'];
	        $row->opt2= $data['opt2'];
	        $row->opt3 = $data['opt3'];
	        $row->opt4 = $data['opt4'];
			$row->ans = $data['ans'];
			$row->exam_id = $data['exam_id'];
	        $row->updated_at = new Zend_Db_Expr('NOW()');

	        return $row->save();
    	}
    	else return false;
	}

	public function pick_random($exam_id)
	{
		if(is_numeric($exam_id))
		{
			$select = $this->select();
			$select->where('exam_id = '.$exam_id);

			$select->order(new Zend_Db_Expr('RAND()'));

			$questions = $this->fetchAll($select);

			$question_ids = array();
			foreach($questions as $q)
			{
				$question_ids[] = array(
					'q'	=> $q->question_id,
					'a'	=> ''
					);
			}

			return $question_ids;
    	}
    	else return array();
	}

	public function get($question_id)
	{
		if($question_id)
		{
			$select = $this->select();
			$select->from($this->_name, array('question_id','question_type', 'question', 'opt1', 'opt2', 'opt3', 'opt4', 'ans'));
			$select->where('question_id = '.$question_id);

			$question = $this->fetchRow($select);
			if($question)
				return $question;
			else return null;
		}
		else return null;
	}
}