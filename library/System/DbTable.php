<?php
/*
** @creator		Alan Pachuau
** @email 		alanpachuau@gmail.com
** @website		alan.pachuau.me
**
** System_DbTable class is and extension of the Zend Db Table Abtract
** This class is intended for providing reusable common db interaction methods
** To use this class, Database table models should extend this class.
*/
class System_DbTable extends Zend_Db_Table_Abstract
{
	protected $_timestamp;
	
	public function init()
	{
		$date = new Zend_Date();
		$this->_timestamp = $date->get(Zend_Date::TIMESTAMP);
	}

	public function paginate($params = array())
	{
		$default = array(
			'limit'		=> 25,
			'page'		=> 1,
			'range'		=> 10,
			'field'	=> array(), // fields to select if few fields are required
			'condition'	=> array() // conditions to apply
		);

		if(empty($params))
			$params = $default;
		if(!isset($params['limit']))
			$params['limit'] = $default['limit'];
		if(!isset($params['page']))
			$params['page'] = $default['page'];
		if(!isset($params['range']))
			$params['range'] = $default['range'];

		$params['table'] = $this->_name;

		$select = $this->select();

		if(isset($params['field']) && !empty($params['field']))
    		$select->from($params['table'], $params['field']);
    	
    	if(isset($params['join_table']) && isset($params['join_on']))
    	{
    		$join_field = array($params['join_table'].'.*');
    		if(isset($params['join_field']))
    			$join_field = $params['join_field'];

    		$select->setIntegrityCheck(false);
    		$select->join($params['join_table'],$params['join_on'], $join_field);
    	}
    	else if(isset($params['join']) && !empty($params['join']))
    	{
    		$select->setIntegrityCheck(false);
    		foreach($params['join'] as $j)
	    		$select->join($j['table'],$j['on'], $j['field']);
    	}

   		if(isset($params['condition']) && !empty($params['condition']))
   		{
            foreach($params['condition'] as $condition)
            {
            	$select->where($condition);
            }
        }
        
        if(isset($params['order']) && !empty($params['order']))
        {
            if(is_array($params['order']))
                foreach($params['order'] as $order)
                    $select->order($order);
            else
                $select->order($params['order']);
        }

        if(isset($params['group']) && !empty($params['group']))
  	  	{
			$select->group($params['group']);
        }

        if(isset($params['debug']) && $params['debug'] == true)
        	echo $select;

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
		
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage($params['limit']);
		$paginator->setCurrentPageNumber($params['page']);
		$paginator->setPageRange($params['range']);

		return $paginator;
	}

	public function rows($params = array())
	{
		$default = array(
			'limit'		=> 25,
			'page'		=> 1,
			'range'		=> 10,
			'field'	=> array(), // fields to select if few fields are required
			'condition'	=> array() // conditions to apply
		);

		if(empty($params))
			$params = $default;
		if(!isset($params['limit']))
			$params['limit'] = $default['limit'];
		if(!isset($params['page']))
			$params['page'] = $default['page'];
		if(!isset($params['range']))
			$params['range'] = $default['range'];

		$params['table'] = $this->_name;

		$select = $this->select();

		if(isset($params['field']) && !empty($params['field']))
    		$select->from($params['table'], $params['field']);
    	
    	if(isset($params['join_table']) && isset($params['join_on']))
    	{
    		$join_field = array($params['join_table'].'.*');
    		if(isset($params['join_field']))
    			$join_field = $params['join_field'];

    		$select->setIntegrityCheck(false);
    		$select->join($params['join_table'],$params['join_on'], $join_field);
    	}
    	else if(isset($params['join']) && !empty($params['join']))
    	{
    		$select->setIntegrityCheck(false);
    		foreach($params['join'] as $j)
	    		$select->join($j['table'],$j['on'], $j['field']);
    	}

   		if(isset($params['condition']) && !empty($params['condition']))
   		{
            foreach($params['condition'] as $condition)
            {
            	$select->where($condition);
            }
        }
        
  	  	if(isset($params['order']) && strlen($params['order']))
  	  	{
			$select->order($params['order']);
        }

        if(isset($params['group']) && !empty($params['group']))
  	  	{
			$select->group($params['group']);
        }

        if(isset($params['debug']) && $params['debug'] == true)
        	echo $select;

        return $this->fetchAll($select);
	}

	public function row($params = array())
	{
		$default = array(
			'limit'		=> 1,
			'page'		=> 1,
			'range'		=> 10,
			'field'	=> array(), // fields to select if few fields are required
			'condition'	=> array() // conditions to apply
		);

		if(empty($params))
			$params = $default;
		if(!isset($params['limit']))
			$params['limit'] = $default['limit'];
		if(!isset($params['page']))
			$params['page'] = $default['page'];
		if(!isset($params['range']))
			$params['range'] = $default['range'];

		$params['table'] = $this->_name;

		$select = $this->select();

		if(isset($params['field']) && !empty($params['field']))
    		$select->from($params['table'], $params['field']);
    	
    	if(isset($params['join_table']) && isset($params['join_on']))
    	{
    		$join_field = array($params['join_table'].'.*');
    		if(isset($params['join_field']))
    			$join_field = $params['join_field'];

    		$select->setIntegrityCheck(false);
    		$select->join($params['join_table'],$params['join_on'], $join_field);
    	}
    	else if(isset($params['join']) && !empty($params['join']))
    	{
    		$select->setIntegrityCheck(false);
    		foreach($params['join'] as $j)
	    		$select->join($j['table'],$j['on'], $j['field']);
    	}

   		if(isset($params['condition']) && !empty($params['condition']))
   		{
            foreach($params['condition'] as $condition)
            {
            	$select->where($condition);
            }
        }
        
  	  	if(isset($params['order']) && strlen($params['order']))
  	  	{
			$select->order($params['order']);
        }

        if(isset($params['group']) && !empty($params['group']))
  	  	{
			// $select->group($params['group']);
        }

        if(isset($params['debug']) && $params['debug'] == true)
        	echo $select;

		return $this->fetchRow($select);
	}
}
