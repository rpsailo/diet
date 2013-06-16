<?php
class Model_Program extends System_DbTable
{
    protected $_name = 'program';

    public function create($data)
    {
        $new_row = $this->createRow();
        $new_row->username = $data['username'];
        $new_row->password = md5($data['password'] . $salt);
        $new_row->password_salt = $salt;
        $new_row->role = $data['role'];
        $new_row->dob = $data['dob'];
        $new_row->phone = $data['phone'];
        $new_row->date_of_joining = $data['date_of_joining'];
        $new_row->educational_qualification = $data['educational_qualification'];
        $new_row->specialization = $data['specialization'];
        $new_row->address = $data['address'];
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        $new_row->loggedin_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

}