<?php 
class Model_Faculty extends System_DbTable
{
	protected $_name='faculty';
	
	public function init()
	{
	}

	public function create($data, $userid)
	{
		$new_row = $this->createRow();
		$new_row->user_id = $userid;
        $new_row->faculty_name = $data['faculty_name'];
        $new_row->faculty_designation = $data['faculty_designation'];
		
       	$new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');

        return $new_row->save();
	}

	public function edit($data, $id)
	{
		if(is_numeric($id))
		{
			$row = $this->find($id)->current();
	        $row->faculty_name = $data['faculty_name'];
	        $row->faculty_designation = $data['faculty_designation'];
	        $row->updated_at = new Zend_Db_Expr('NOW()');
	        return $row->save();
		}
		else return false;        
	}
	
	public function designations()
	{
		$select = $this->select();
		$select->from($this->_name, array('faculty_designation' => new Zend_Db_Expr('DISTINCT(`faculty_designation`)')));
		$select->order('faculty_designation ASC');
		return $this->fetchAll($select);
	}
}