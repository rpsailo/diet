<?php
class Model_SubDivisionBlock extends System_DbTable
{
    protected $_name = 'sub_division_block';

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

    public function districts()
    {
        return array(
            'Aizawl' => 'Aizawl',
            'Champhai' => 'Champhai',
            'Kolasib' => 'Kolasib',
            'Lawngtlai' => 'Lawngtlai',
            'Lunglei' => 'Lunglei',
            'Mamit' => 'Mamit',
            'Saiha' => 'Saiha',
            'Serchhip' => 'Serchhip'
            );
    }

    public function getByDistrict($district)
    {
        $select = $this->select();
        
        if($district != '')
            $select->where("`district` = '".$district."'");

        $select->order('name asc');
        return $this->fetchAll($select);
    }

}