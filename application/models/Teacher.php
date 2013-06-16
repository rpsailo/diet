<?php
class Model_Teacher extends System_DbTable
{
    protected $_name = 'teacher';
    
    public function create($data)
    {
        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();
        $new_row = $this->createRow();
        $new_row->name = $data['name'];
        $new_row->address = $data['address'];
        $new_row->phone = $data['phone'];
        $new_row->year_of_establishment = $data['year_of_establishment'];
        $new_row->type = $data['type'];
        $new_row->no_of_teachers = $data['no_of_teachers'];
        $new_row->user_id = $loggedin_user->id;
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

}