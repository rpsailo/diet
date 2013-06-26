<?php
class Model_Program extends System_DbTable
{
    protected $_name = 'program';
    
    public function create($data)
    {
        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();
        $new_row = $this->createRow();
        $new_row->name = $data['name'];
        $new_row->duration = $data['duration'];
        $new_row->no_of_intake = $data['no_of_intake'];
        $new_row->target = $data['target'];
        $new_row->objectives = $data['objectives'];
        $new_row->faculties = implode(',', $data['faculties']);
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
        $row->name = $data['name'];
        $row->duration = $data['duration'];
        $row->no_of_intake = $data['no_of_intake'];
        $row->target = $data['target'];
        $row->objectives = $data['objectives'];
        $row->faculties = implode(',', $data['faculties']);
        $row->user_id = $loggedin_user->id;
        $row->updated_at = new Zend_Db_Expr('NOW()');
        return $row->save();
    }

    public function all()
    {
        $select = $this->select();
        $select->order('name asc');
        return $this->fetchAll($select);
    }

    public function stats()
    {
        return $this->all()->count();
    }

}