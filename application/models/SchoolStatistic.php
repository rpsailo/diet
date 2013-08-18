<?php
class Model_SchoolStatistic extends System_DbTable
{
    protected $_name = 'school_statistic';
    
    public function create($data, $type)
    {
        if($type == 'Primary School')
        {
            $new_row = $this->createRow();
            $new_row->school_id = $data['school_id'];
            $new_row->year = $data['year'];
            $new_row->boys_pre = $data['boys_pre'];
            $new_row->girls_pre = $data['girls_pre'];
            $new_row->boys_1 = $data['boys_1'];
            $new_row->girls_1 = $data['girls_1'];
            $new_row->boys_2 = $data['boys_2'];
            $new_row->girls_2 = $data['girls_2'];
            $new_row->boys_3 = $data['boys_3'];
            $new_row->girls_3 = $data['girls_3'];
            $new_row->boys_4 = $data['boys_4'];
            $new_row->girls_4 = $data['girls_4'];
            $new_row->boys_5 = 0;
            $new_row->girls_5 = 0;
            $new_row->boys_6 = 0;
            $new_row->girls_6 = 0;
            $new_row->boys_7 = 0;
            $new_row->girls_7 = 0;
            $new_row->boys_8 = 0;
            $new_row->girls_8 = 0;
            $new_row->teachers = $data['teachers'];
            $new_row->created_at = new Zend_Db_Expr('NOW()');
            $new_row->updated_at = new Zend_Db_Expr('NOW()');
            return $new_row->save();
        }
        else if($type == 'Middle School')
        {
            $new_row = $this->createRow();
            $new_row->school_id = $data['school_id'];
            $new_row->year = $data['year'];
            $new_row->boys_pre = 0;
            $new_row->girls_pre = 0;
            $new_row->boys_1 = 0;
            $new_row->girls_1 = 0;
            $new_row->boys_2 = 0;
            $new_row->girls_2 = 0;
            $new_row->boys_3 = 0;
            $new_row->girls_3 = 0;
            $new_row->boys_4 = 0;
            $new_row->girls_4 = 0;
            $new_row->boys_5 = $data['boys_5'];
            $new_row->girls_5 = $data['girls_5'];
            $new_row->boys_6 = $data['boys_6'];
            $new_row->girls_6 = $data['girls_6'];
            $new_row->boys_7 = $data['boys_7'];
            $new_row->girls_7 = $data['girls_7'];
            $new_row->boys_8 = $data['boys_8'];
            $new_row->girls_8 = $data['girls_8'];
            $new_row->teachers = $data['teachers'];
            $new_row->created_at = new Zend_Db_Expr('NOW()');
            $new_row->updated_at = new Zend_Db_Expr('NOW()');
            return $new_row->save();
        }
        else return null;
        
    }

    public function edit($data, $type)
    {
        if($type == 'Primary School')
        {
            $row = $this->find($data['id'])->current();

            if(isset($data['school_id']))
                $row->school_id = $data['school_id'];
            
            $row->year = $data['year'];
            $row->boys_pre = $data['boys_pre'];
            $row->girls_pre = $data['girls_pre'];
            $row->boys_1 = $data['boys_1'];
            $row->girls_1 = $data['girls_1'];
            $row->boys_2 = $data['boys_2'];
            $row->girls_2 = $data['girls_2'];
            $row->boys_3 = $data['boys_3'];
            $row->girls_3 = $data['girls_3'];
            $row->boys_4 = $data['boys_4'];
            $row->girls_4 = $data['girls_4'];
            $row->boys_5 = 0;
            $row->girls_5 = 0;
            $row->boys_6 = 0;
            $row->girls_6 = 0;
            $row->boys_7 = 0;
            $row->girls_7 = 0;
            $row->boys_8 = 0;
            $row->girls_8 = 0;
            $row->teachers = $data['teachers'];

            $row->updated_at = new Zend_Db_Expr('NOW()');
            return $row->save();
        }
        else if($type == 'Middle School')
        {
            $row = $this->find($data['id'])->current();

            if(isset($data['school_id']))
                $row->school_id = $data['school_id'];
            
            $row->year = $data['year'];
            $row->boys_pre = 0;
            $row->girls_pre = 0;
            $row->boys_1 = 0;
            $row->girls_1 = 0;
            $row->boys_2 = 0;
            $row->girls_2 = 0;
            $row->boys_3 = 0;
            $row->girls_3 = 0;
            $row->boys_4 = 0;
            $row->girls_4 = 0;
            $row->boys_5 = $data['boys_5'];
            $row->girls_5 = $data['girls_5'];
            $row->boys_6 = $data['boys_6'];
            $row->girls_6 = $data['girls_6'];
            $row->boys_7 = $data['boys_7'];
            $row->girls_7 = $data['girls_7'];
            $row->boys_8 = $data['boys_8'];
            $row->girls_8 = $data['girls_8'];
            $row->teachers = $data['teachers'];
            $row->updated_at = new Zend_Db_Expr('NOW()');
            return $row->save();
        }
        else return null;
        
    }

    public function all()
    {
        $select = $this->select();
        $select->order('year desc');
        return $this->fetchAll($select);
    }

    public function years()
    {
        $select = $this->select();
        $select->order('year asc');
        $select->from($this->_name, array('year'=>new Zend_Db_Expr('DISTINCT(`year`)') ));
        return $this->fetchAll($select);
    }

    public function minMaxYear($school_id = null)
    {
        $select = $this->select();

        if($school_id != null)
            $select->where('school_id = '.$school_id);
        
        $select->from($this->_name, array('min'=>new Zend_Db_Expr('MIN(`year`)'), 'max'=>new Zend_Db_Expr('MAX(`year`)') ));
        return $this->fetchRow($select);
    }

    public function currentTeachers($school_id = null)
    {
        if($school_id != null)
        {
            $select = $this->select();
            $select->where("school_id = ".$school_id);
            $select->order('year desc');
            $data = $this->fetchRow($select);

            if($data)
                return $data->teachers;
            else
                return 0;
        }
        else
            return 0;
    }

    public function currentStudents($school_id = null)
    {
        if($school_id != null)
        {
            $schoolmodel = new Model_School();
            
            $school = $schoolmodel->find($school_id)->current();

            $select = $this->select();
            
            if($school->level == 'Primary School')            
                $select->from($this->_name, array('total'=>new Zend_Db_Expr('(`boys_pre`+`boys_1`+`boys_2`+`boys_3`+`boys_4`+`girls_pre`+`girls_1`+`girls_2`+`girls_3`+`girls_4`)') ));
            else if($school->level == 'Middle School')            
                $select->from($this->_name, array('total'=>new Zend_Db_Expr('(`boys_5`+`boys_6`+`boys_7`+`boys_8`+`girls_5`+`girls_6`+`girls_7`+`girls_8`)') ));
            
            $select->where("school_id = ".$school_id);
            $select->order('year desc');
            $data = $this->fetchRow($select);

            if($data)
                return $data->total;
            else
                return 0;
        }
        else
            return 0;
    }

    public function currentStatistic($school_id = null)
    {
        if($school_id != null)
        {
            $schoolmodel = new Model_School();
            
            $school = $schoolmodel->find($school_id)->current();

            $select = $this->select();
            
            if($school->level == 'Primary School')            
                $select->from($this->_name, array('teachers', 'students'=>new Zend_Db_Expr('(`boys_pre`+`boys_1`+`boys_2`+`boys_3`+`boys_4`+`girls_pre`+`girls_1`+`girls_2`+`girls_3`+`girls_4`)') ));
            else if($school->level == 'Middle School')            
                $select->from($this->_name, array('teachers', 'students'=>new Zend_Db_Expr('(`boys_5`+`boys_6`+`boys_7`+`boys_8`+`girls_5`+`girls_6`+`girls_7`+`girls_8`)') ));
            
            $select->where("school_id = ".$school_id);
            $select->order('year desc');
            $data = $this->fetchRow($select);

            return $data;
        }
        else
            return null;
    }

    public function schoolStatistics($school_id = null)
    {
        if($school_id != null)
        {
            $schoolmodel = new Model_School();
            
            $school = $schoolmodel->find($school_id)->current();

            $select = $this->select();
            
            if($school->level == 'Primary School')            
                $select->from($this->_name, array('year', 'boys_pre', 'boys_1', 'boys_2', 'boys_3', 'boys_4', 'girls_pre', 'girls_1', 'girls_2', 'girls_3', 'girls_4', 'teachers', 'students'=>new Zend_Db_Expr('(`boys_pre`+`boys_1`+`boys_2`+`boys_3`+`boys_4`+`girls_pre`+`girls_1`+`girls_2`+`girls_3`+`girls_4`)') ));
            else if($school->level == 'Middle School')            
                $select->from($this->_name, array('year', 'boys_1'=>'boys_5', 'boys_2'=>'boys_6', 'boys_3'=>'boys_7', 'boys_4'=>'boys_8', 'girls_1'=>'girls_5', 'girls_2'=>'girls_6', 'girls_3'=>'girls_7', 'girls_4'=>'girls_8', 'teachers', 'students'=>new Zend_Db_Expr('(`boys_5`+`boys_6`+`boys_7`+`boys_8`+`girls_5`+`girls_6`+`girls_7`+`girls_8`)') ));
            
            $select->where("school_id = ".$school_id);
            $select->order('year desc');
            $data = $this->fetchAll($select);

            return $data;
        }
        else
            return null;
    }

    public function teachersInDivBlock($sub_division = null)
    {
        if($sub_division != null)
        {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array("a"=>$this->_name), array("name", "type"));
            $select->join(array("b"=>'school'), "a.school_id=b.school_id", array() );
            $select->where("b.sub_division = ".$sub_division);
            return $this->fetchAll($select);
        }
        else
            return null;
    }
}