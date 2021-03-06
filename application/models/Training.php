<?php
class Model_Training extends System_DbTable
{
    protected $_name = 'training';
    
    public function create($data)
    {
        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();

        $new_row = $this->createRow();
        $new_row->program_id = $data['program_id'];
        $new_row->teacher_id = $data['teacher_id'];
        $new_row->from = $data['from'];
        $new_row->to = $data['to'];
        $new_row->status = $data['status'];

        $new_row->user_id = $loggedin_user->id;
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

    public function training($teacher_id)
    {
        $select = $this->select();
        $select->where('`teacher_id` = ?', $teacher_id);
        $select->order('created_at desc');
        return $this->fetchAll($select);
    }

    public function teacherCount($teacher_id)
    {
        $select = $this->select();
        $select->where('`teacher_id` = ?', $teacher_id);
        $trainings = $this->fetchAll($select);
        return sizeof($trainings->toArray());
    }

    public function trainingCount($teacher_id)
    {
        $trainings = $this->training($teacher_id);
        if($trainings->count())
        {
           return $trainings->count(); 
        }
        else
            return 0;
    }

    public function teachersInProgram($program_id = null)
    {
        if($program_id != null)
        {
            $select = $this->select();
            $select->from($this->_name, array('teacher'=>new Zend_Db_Expr('DISTINCT(`teacher_id`)')));
            $select->where("program_id = ".$program_id);
            return $this->fetchAll($select);
        }
        else
            return null;
    }
}