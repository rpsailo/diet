<?php
class Model_Program extends System_DbTable
{
    protected $_name = 'program';
    
    public function create($data)
    {
        $auth = Zend_Auth::getInstance();
        $loggedin_user = $auth->getIdentity();
        $new_row = $this->createRow();
        $new_row->name = $data['programname'];
        $new_row->duration = $data['duration'];
        $new_row->target = $data['target'];
        $new_row->objectives = $data['objectives'];
        $new_row->user_id = $loggedin_user->id;
        $new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
    }

}