<?php
class Model_User extends System_DbTable
{
    protected $_name = 'user';

    public function create($data)
    {
        $salt = '';
        for($i=0; $i<32; $i++)
            $salt .= chr(rand(33, 126));

        $new_row = $this->createRow();
        $new_row->username = $data['username'];
        $new_row->password = md5($data['password'] . $salt);
        $new_row->password_salt = $salt;
        $new_row->name = ($data['name'] != '')? $data['name'] : $data['username'];
        $new_row->role = $data['role'];
        $new_row->dob = $data['dob'];
        $new_row->phone = $data['phone'];
        $new_row->date_of_joining = $data['date_of_joining'];
        $new_row->educational_qualification = $data['educational_qualification'];
        $new_row->professional_qualification = $data['professional_qualification'];
        $new_row->other_qualification = $data['other_qualification'];
        $new_row->present_address = $data['present_address'];
        $new_row->permanent_address = $data['permanent_address'];
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        $new_row->loggedin_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

    public function edit($data, $id)
    {
        if(is_numeric($id))
        {
            $row = $this->find($id)->current();
            $row->name = ($data['name'] != '')? $data['name'] : $row->username;
            $row->role = $data['role'];
            $row->dob = $data['dob'];
            $row->phone = $data['phone'];
            $row->date_of_joining = $data['date_of_joining'];
            $row->educational_qualification = $data['educational_qualification'];
            $row->professional_qualification = $data['professional_qualification'];
            $row->other_qualification = $data['other_qualification'];
            $row->present_address = $data['present_address'];
            $row->permanent_address = $data['permanent_address'];
            $row->updated_at = new Zend_Db_Expr('NOW()');
            return $row->save();
        }
        else return false; 
    }

    public function change_password($password, $id)
    {
        if(is_numeric($id) && strlen($password))
        {
            $salt = '';
            for($i=0; $i<32; $i++)
                $salt .= chr(rand(33, 126));

            $row = $this->find($id)->current();
            if($row)
            {
                $row->password = md5($password . $salt);
                $row->password_salt = $salt;
                $row->updated_at = new Zend_Db_Expr('NOW()');
                return $row->save();
            }
        }
        else return false;
    }

    public function faculties($ids = '')
    {
        $select = $this->select();
        
        if(is_array($ids) && !empty($ids))
        {
            $select->where('id in ('.implode($ids).')');
        }
        else if($ids != '')
        {
            //$select->where('id in ('.$ids.')');
        }

        $select->where('role = "faculty"');
        $select->order('name asc');
        return $this->fetchAll($select);
    }

    public function all()
    {
        $select = $this->select();
        $select->order('username asc');
        return $this->fetchAll($select);
    }

    public function stats()
    {
        return $this->all()->count();
    }
}