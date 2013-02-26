<?php
namespace Trade\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class AuthorizationPlugin extends AbstractPlugin
{
	protected $acl;
	
	public function __construct()
	{
		// Set up ACL
		$this->acl = new Acl();

		// Add roles
		$this->acl->addRole(new Role('anonymous'));
		$this->acl->addRole(new Role('member'), 'anonymous');
		$this->acl->addRole(new Role('admin'), 'member');

		// Add controller resources
		$this->acl->addResource(new Resource('Application\Controller\Index'));
		$this->acl->addResource(new Resource('Trade\Controller\Album'));
		$this->acl->addResource(new Resource('Trade\Controller\Member'));
		$this->acl->addResource(new Resource('Trade\Controller\Prune'));
		
		// Allow or deny access to views
		$this->acl->allow('anonymous', 'Application\Controller\Index', array('index', 'privacy'));
		$this->acl->allow('anonymous', 'Trade\Controller\Member', array('login', 'join'));
		$this->acl->allow('anonymous', 'Trade\Controller\Prune', array('pruneLog'));
		$this->acl->allow('member', 'Trade\Controller\Album', array('add', 'delete', 'edit', 'index'));
		$this->acl->allow('member', 'Trade\Controller\Member', array('view', 'logout'));
	}

	public function getAcl()
	{
		return $this->acl;
	}
	
	public function isAuthorized($role, $controller, $action)
	{
		return $this->getAcl()->isAllowed($role, $controller, $action);
	}
}