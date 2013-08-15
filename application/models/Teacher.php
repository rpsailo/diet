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
        $new_row->present_address = $data['present_address'];
        $new_row->permanent_address = $data['permanent_address'];
        $new_row->district = $data['district'];
        $new_row->sub_division = $data['sub_division'];
        $new_row->date_of_joining = $data['date_of_joining'];
        $new_row->educational_qualification = $data['educational_qualification'];
        $new_row->professional_qualification = $data['professional_qualification'];
        $new_row->other_qualification = $data['other_qualification'];
        $new_row->year_of_retirement = new Zend_Db_Expr("YEAR(DATE_ADD('".$data['dob']."', INTERVAL 60 YEAR))");
        $new_row->tet = $data['tet'];
        $new_row->main_subject_taught = $data['main_subject_taught'];
        $new_row->status = $data['status'];
        $new_row->achievement = $data['achievement'];
        $new_row->no_of_training = 0;
        
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
        $row->present_address = $data['present_address'];
        $row->permanent_address = $data['permanent_address'];
        $row->district = $data['district'];
        $row->sub_division = $data['sub_division'];
        $row->date_of_joining = $data['date_of_joining'];
        $row->educational_qualification = $data['educational_qualification'];
        $row->professional_qualification = $data['professional_qualification'];
        $row->other_qualification = $data['other_qualification'];
        $row->year_of_retirement = new Zend_Db_Expr("YEAR(DATE_ADD('".$data['dob']."', INTERVAL 60 YEAR))");
        $row->tet = $data['tet'];
        $row->main_subject_taught = $data['main_subject_taught'];
        $row->status = $data['status'];
        $row->achievement = $data['achievement'];

        $trainingmodel = new Model_Training();
        $row->no_of_training = $trainingmodel->trainingCount($id);
        
        $row->user_id = $loggedin_user->id;
        $row->updated_at = new Zend_Db_Expr('NOW()');
        return $row->save();
    }

    public function professionalQualifications()
    {
        $select = $this->select();
        $select->from($this->_name, array('professional_qualification' => new Zend_Db_Expr('DISTINCT(`professional_qualification`)')));
        $select->order('professional_qualification asc');
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

    public function all()
    {
        $select = $this->select();
        $select->order('name asc');
        return $this->fetchAll($select);
    }

    public function distinctDivBlock()
    {
        $select = $this->select();
        $select->from($this->_name, array('sub_divison'=>new Zend_Db_Expr('DISTINCT(`sub_divison`)')));
        $select->order('sub_divison asc');
        return $this->fetchAll($select);
    }

    public function inDivBlock($sub_division = null)
    {
        if($sub_division != null)
        {
            $select = $this->select();
            $select->where("sub_division = '".$sub_division."'");
            $select->order("name asc");
            return $this->fetchAll($select);
        }
        else
            return 0;
    }

    public function stats()
    {
        return $this->all()->count();
    }

    public function trainedUntrained($level = '', $status = '')
    {
        if($level != '')
        {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array("a"=>$this->_name), array('a.*'));
            $select->join(array("b"=>'school'), "a.school_id = b.id", array('sub_division', 'type', 'level'));

            $select->where("level = '".$level."'");

            if($status != '')
            {
                if($status == 'trained')
                    $select->where("professional_qualification = 'Master' OR professional_qualification = 'Bachelor' OR professional_qualification = 'Diploma'");
                elseif($status == 'untrained')
                    $select->where("professional_qualification = 'Nil'");
                elseif($status == 'ongoing')
                    $select->where("professional_qualification = 'Ongoing'");
            }

            return $this->fetchAll($select);
        }
        else
            return null;
    }
}