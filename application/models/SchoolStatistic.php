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
            $new_row->created_at = new Zend_Db_Expr('NOW()');
            $new_row->updated_at = new Zend_Db_Expr('NOW()');
            return $new_row->save();
        }
        else if($type == 'Middle School')
        {
            $new_row = $this->createRow();
            $new_row->school_id = $data['school_id'];
            $new_row->year = $data['year'];
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

            $row->updated_at = new Zend_Db_Expr('NOW()');
            return $row->save();
        }
        else if($type == 'Middle School')
        {
            $row = $this->find($data['id'])->current();

            if(isset($data['school_id']))
                $row->school_id = $data['school_id'];
            
            $row->year = $data['year'];
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
}