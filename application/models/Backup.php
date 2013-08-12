<?php
class Model_Backup extends System_DbTable
{
    protected $_name = 'backup';
    
    public function create($data)
    {
        $new_row = $this->createRow();
        $new_row->file = $data['file'];

        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

    public function all()
    {
        $select = $this->select();
        $select->order('created_at asc');
        return $this->fetchAll($select);
    }
    
    public function stats()
    {
        return $this->all()->count();
    }
}