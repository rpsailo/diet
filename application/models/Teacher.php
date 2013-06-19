<?php
class Model_Teacher extends System_DbTable
{
    protected $_name = 'teacher';
    
    public function create($data)
    {
        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();

        $new_row = $this->createRow();
        $new_row->school_id = $data['school_id'];
        $new_row->name = $data['name'];
        $new_row->sex = $data['sex'];
        $new_row->dob = $data['dob'];
        $new_row->address = $data['address'];
        $new_row->locality = ucwords($data['locality']);
        $new_row->date_of_joining = $data['date_of_joining'];
        $new_row->educational_qualification = $data['educational_qualification'];
        $new_row->specialization = $data['specialization'];
        $new_row->training_status = $data['training_status'];
        $new_row->year_of_retirement = $data['year_of_retirement'];
        
        $new_row->user_id = $loggedin_user->id;
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

    public function edit($data, $id)
    {
        if(!is_numeric($id))
            return false;

        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();

        $row = $this->find($id)->current();
        $row->school_id = $data['school_id'];
        $row->name = $data['name'];
        $row->sex = $data['sex'];
        $row->dob = $data['dob'];
        $row->address = $data['address'];
        $row->locality = ucwords($data['locality']);
        $row->date_of_joining = $data['date_of_joining'];
        $row->educational_qualification = $data['educational_qualification'];
        $row->specialization = $data['specialization'];
        $row->training_status = $data['training_status'];
        $row->year_of_retirement = $data['year_of_retirement'];
        
        $row->user_id = $loggedin_user->id;
        $row->updated_at = new Zend_Db_Expr('NOW()');
        return $row->save();
    }

    public function specializations()
    {
        $select = $this->select();
        $select->from($this->_name, array('specialization' => new Zend_Db_Expr('DISTINCT(`specialization`)')));
        $select->order('specialization asc');
        return $this->fetchAll($select);
    }

    public function typeahead($name, $limit)
    {
        if($name == '')
            return null;

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, array('id', 'school_id', 'name'));
        $select->join('school', $this->_name.'.school_id = school.id', array('school_name'=>'school.name'));

        $select->where($this->_name.".`name` LIKE '%".$name."%'");
        
        if(is_numeric($limit))
            $select->limit($limit);
        
        $select->order('teacher.name asc');
        return $this->fetchAll($select);
    }
}