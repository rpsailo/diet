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

		$this->acl->addResource(new Zend_Acl_Resource("default:school:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:school:remove"), "default");

		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:add"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:index"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:edit"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:remove"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:view"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:training"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:add-training"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:edit-training"), "default");
		$this->acl->addResource(new Zend_Acl_Resource("default:teacher:remove-training"), "default");
		
	}
	
	public function setPrivileges()
	{
		$this->acl->deny(array(
			'public',
			'faculty',
			'administrator'
			), null);

		$this->acl->allow('faculty', array(
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