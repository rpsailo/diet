<?php
class Model_SchoolStatistic extends System_DbTable
{
    protected $_name = 'school_statistic';
    
    public function create($data)
    {
        $new_row = $this->createRow();
        $new_row->school_id = $data['school_id'];
        $new_row->boys = $data['boys'];
        $new_row->class = $data['class'];
        $new_row->girls = $data['girls'];
        $new_row->year = $data['year'];
        
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

    public function edit($data, $id)
    {
        if(is_numeric($id))
        {
            $row = $this->find($id)->current();
            $row->class = $data['class'];
            $row->boys = $data['boys'];
            $row->girls = $data['girls'];
            $row->year = $data['year'];

            $row->updated_at = new Zend_Db_Expr('NOW()');
            return $row->save();
        }
        else return false;
    }

    public function all()
    {
        $select = $this->select();
        $select->order('year desc');
        return $this->fetchAll($select);
    }
}