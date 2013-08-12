<?php

class BackupController extends Zend_Controller_Action
{
	protected $auth;

	protected $backupmodel;
	protected $backupform;

	public function init()
	{
		$this->backupmodel = new Model_Backup();
		$this->backupform = new Form_Backup();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->_uploads_rel = SITE_PATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."backup".DIRECTORY_SEPARATOR;
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
    	$limit = $this->_request->getParam('limit', 20);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'limit' 	=> $limit,
    		'page'		=> $page,
    		'order'		=> 'created_at asc',
    		'condition'	=> array()
		);

		if($this->_request->isPost())
		{
			error_reporting(E_ALL);
			ini_set('display_errors',1);
			ini_set('memory_limit','1500M');
			set_time_limit(0);

			$config = Zend_Registry::get('config');
			
			$filename = $this->backup($config->database->params->host,$config->database->params->username,$config->database->params->password,$config->database->params->dbname);

			$data = array(
				'file' => '/uploads/backup/'.$filename
				);

			$id = $this->backupmodel->create($data);

			$backup = $this->backupmodel->find($id)->current();

            $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New backup added at <b>'.date('d M Y, h:i:sA',strtotime($backup->created_at)).'</b>.', "status"=>"success"));
            $this->_redirect("/backup/");
    	}

    	$this->view->data = $this->backupmodel->paginate($params);
    	$this->view->form = $this->backupform;
	}

	public function backup($host,$user,$pass,$name,$tables = '*')
	{
		//save file
		$filename = 'db-backup-'.time().'.sql.gz';
		$handle = gzopen($this->_uploads_rel . $filename, 'w9');

		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);

		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}

		//cycle through
		foreach($tables as $table)
		{
			$result = mysql_query('SELECT * FROM ' . $table);
			$num_fields = mysql_num_fields($result);

			$return = 'DROP TABLE IF EXISTS '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return .= "\n\n".$row2[1].";\n\n";
			gzwrite($handle,$return);
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$return = 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = str_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}

					$return.= ");\n";
					gzwrite($handle,$return);
				}
			}
			$return ="\n\n\n";
			gzwrite($handle,$return);
		}

		gzclose($handle);

		return $filename;
	}

	public function removeAction()
	{
		$id = $this->_getParam('id');
		if($id)
		{
			$backup = $this->backupmodel->find($id)->current();
			
			$result = $this->backupmodel->delete('id = '.$id);
			if($result)
			{
				if(file_exists(SITE_PATH.$backup->file))
					unlink(SITE_PATH.$backup->file);
			}

            $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> <b>'.date('d M Y, h:i:sA',strtotime($backup->created_at)).'</b> backup deleted.', "status"=>"success"));
            $this->_redirect("/backup/");
		}
	}
}



