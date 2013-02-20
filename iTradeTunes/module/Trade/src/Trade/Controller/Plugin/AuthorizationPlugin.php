<?php
namespace Trade\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class AuthorizationPlugin extends AbstractPlugin
{
	public function isAuthorized($role, $controller, $action)
	{
		// Set up ACL
		$acl = new Acl();

		// Add roles
		$acl->addRole(new Role('anonymous'));
		$acl->addRole(new Role('member'), 'anonymous');
		$acl->addRole(new Role('admin'), 'member');

		// Add controller resources
		$acl->addResource(new Resource('Application\Controller\Index'));
		$acl->addResource(new Resource('Trade\Controller\Album'));
		$acl->addResource(new Resource('Trade\Controller\Member'));
		$acl->addResource(new Resource('Trade\Controller\Prune'));
		
		// Allow or deny access to views
		$acl->allow('anonymous', 'Application\Controller\Index', array('index', 'privacy'));
		$acl->allow('anonymous', 'Trade\Controller\Member', array('login', 'join'));
		$acl->allow('member', 'Trade\Controller\Album', array('add', 'delete', 'edit', 'index'));
		$acl->allow('member', 'Trade\Controller\Member', array('view', 'logout'));

		return $acl->isAllowed($role, $controller, $action);
	}
}
