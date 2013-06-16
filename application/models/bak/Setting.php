<?php
class Model_Setting extends System_DbTable
{
    protected $_name = 'setting';

    public function update_data($key, $data)
    {
        $this->update(array('data'=>$data), "`key` = '".$key."'");
    }

    public function get_data($key)
	{
		if($key)
		{
			$select = $this->select();
			$select->where('`key` = "'.$key.'"');
			$row = $this->fetchRow($select);
			if($row)
				return $row->data;
			else return null;
		}
		else return null;
	}
}