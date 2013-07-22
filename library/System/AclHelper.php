<?php
class System_AclHelper
{
	public $acl;
	
	public function __construct()
	{
		$this->acl = new Zend_Acl();
	}
	
	public function setRoles()
	{
		$this->acl->addRole(new Zend_Acl_Role('public'));
		$this->acl->addRole(new Zend_Acl_Role('faculty'));
		$this->acl->addRole(new Zend_Acl_Role('administrator'));
	}
	
	public function setResources()
	{
		$this->acl->addResource(new Zend_Acl_Resource("default"));
		
		$this->acl->addResource(new Zend_Acl_Resource("default:index:index"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:change-password:index"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:error:error"), "default");
		
		$this->acl->addResource(new Zend_Acl_Resource("default:auth:login"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:auth:logout"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:user:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:user:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:user:view"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:user:remove"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:user:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:user:reset-password"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:program:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:remove"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:view"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:trainee"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:add-trainee"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:program:remove-trainee"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:school:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:remove"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:view"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:fetch-by-level"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:new-statistic"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:remove-statistic"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:edit-statistic"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:statistics"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:school-statistic:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school-statistic:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school-statistic:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school-statistic:remove"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school-statistic:view"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:remove"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:view"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:training"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:add-training"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:remove-training"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:change-training-status"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:change-training-date"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:typeahead"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:sub-division:fetch-by-district"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:report:index"), "default");
		
	}
	
	public function setPrivileges()
	{
		$this->acl->deny(array(
			'public',
			'faculty',
			'administrator'
			), null);

		$this->acl->allow('faculty', array(
			"default:index:index",
			"default:change-password:index",
			"default:error:error",
			"default:auth:login",
			"default:auth:logout",
			"default:user:reset-password",
			"default:user:view",
			"default:user:edit",
			"default:program:add",
			"default:program:index",
			"default:program:edit",
			"default:program:view",
			"default:program:trainee",
			"default:program:add-trainee",
			"default:program:remove-trainee",
			"default:school:add",
			"default:school:index",
			"default:school:edit",
			"default:school:view",
			"default:school:statistics",
			"default:school:edit-statistic",
			"default:school-statistic:index",
			"default:school-statistic:add",
			"default:school-statistic:view",
			"default:school-statistic:edit",
			"default:school:fetch-by-level",
			"default:teacher:add",
			"default:teacher:index",
			"default:teacher:edit",
			"default:teacher:view",
			"default:teacher:training",
			"default:teacher:add-training",
			"default:teacher:change-training-status",
			"default:teacher:change-training-date",
			"default:teacher:typeahead",
			"default:sub-division:fetch-by-district",
			"default:report:index"
			));
		$this->acl->allow('administrator', null);
		$this->acl->allow(null, array(
			'default:auth:login',
			'default:auth:logout'
			));
	}
	
	public function setAcl()
	{
		Zend_Registry::set('acl', $this->acl);
	}
}