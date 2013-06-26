<?php
class Model_School extends System_DbTable
{
    protected $_name = 'school';
    
    public function create($data)
    {
        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();
        $new_row = $this->createRow();
        $new_row->name = $data['name'];
        $new_row->address = $data['address'];
        $new_row->sub_division = $data['sub_division'];
        $new_row->phone = $data['phone'];
        $new_row->year_of_establishment = $data['year_of_establishment'];
        $new_row->type = $data['type'];
        $new_row->level = $data['level'];
        $new_row->no_of_teachers = $data['no_of_teachers'];
        $new_row->user_id = $loggedin_user->id;
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

    public function edit($data, $id)
    {
        if(is_numeric($id))
        {
            $auth = Zend_Auth::getInstance();
            $loggedin_user = $auth->getIdentity();
            
            $row = $this->find($id)->current();
            $row->name = $data['name'];
            $row->address = $data['address'];
            $row->sub_division = $data['sub_division'];
            $row->phone = $data['phone'];
            $row->year_of_establishment = $data['year_of_establishment'];
            $row->type = $data['type'];
            $row->level = $data['level'];
            $row->no_of_teachers = $data['no_of_teachers'];
            $row->user_id = $loggedin_user->id;
            $row->updated_at = new Zend_Db_Expr('NOW()');
            return $row->save();
        }
        else return false;
    }

    public function all()
    {
        $select = $this->select();
        $select->order('name asc');
        return $this->fetchAll($select);
    }

    public function types()
    {
        $select = $this->select();
        $select->from($this->_name, array('typename' => new Zend_Db_Expr('DISTINCT(`type`)')));
        $select->order('type asc');
        return $this->fetchAll($select);
    }

    public function getByLevel($level)
    {
        $select = $this->select();
        
        if($level != '')
            $select->where("`level` = '".$level."'");

        $select->order('name asc');
        return $this->fetchAll($select);
    }

    public function stats()
    {
        return $this->all()->count();
    }
}