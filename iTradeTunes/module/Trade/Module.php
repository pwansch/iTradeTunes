<?php

namespace Trade;
use Trade\Model\AlbumTable;
use Trade\Model\Album;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Session\Container;
use Zend\Http\Response;

class Module implements ConsoleUsageProviderInterface
{
	public function onBootstrap(MvcEvent $e)
	{
		$eventManager = $e->getApplication()->getEventManager();
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkAcl'), 100);
		$eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
			$controller = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			$config = $e->getApplication()->getServiceManager()->get('config');
			if (isset($config['module_layouts'][$moduleNamespace])) {
				$controller->layout($config['module_layouts'][$moduleNamespace]);
			}
		}, 100);		
	}
	
	public function checkAcl(MvcEvent $e)
	{
		$application = $e->getApplication();
		$sm = $application->getServiceManager();

		// Get the user, controller and action for the request
		$matches = $e->getRouteMatch();
		$controller = $matches->getParam('controller');
		$action = $matches->getParam('action');
		$auth = $sm->get('auth');
		$session = $sm->get('session');
		$role = 'anonymous';
		if ($auth->hasIdentity()) {
			// Identity exists, so get the role
			$role = $session->role;
		}

		// If the request is not authorized, redirect to the login view
		if (!$sm->get('ControllerPluginManager')->get('AuthorizationPlugin')->isAuthorized($role, $controller, $action)) {
			// Assemble the url
			$router = $e->getRouter();
			$url = $router->assemble(array(), array('name' => 'home'));

			// Populate and return the response
			$response = $e->getResponse();
			$response->setStatusCode(Response::STATUS_CODE_302);
			$response->getHeaders()->addHeaderLine('Location', $url);
			$e->stopPropagation();
			return $response;
		} 
	}	
	
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
						),
				),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getServiceConfig()
	{
		return array(				
				'services' => array(
						 'auth' => new AuthenticationService(),
						 'session' => new Container(),
						),				
				'factories' => array(
						'authAdapter' => function($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$authAdapter = new AuthAdapter($dbAdapter);
							$authAdapter->setTableName('member')
							            ->setIdentityColumn('email_address')
							            ->setCredentialColumn('password_encrypted')
							            ->setCredentialTreatment('MD5(?) AND status = 1');							
							return $authAdapter;
						},
						'Trade\Model\AlbumTable' =>  function($sm) {
							$tableGateway = $sm->get('AlbumTableGateway');
							$table = new AlbumTable($tableGateway);
							return $table;
						},
						'AlbumTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Album());
							return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
						},						
						
						
				),
		); 
	}

	public function getConsoleUsage(Console $console){
		return array(
				// Describe available commands
				'prune log [--verbose|-v]' => 'Prune log',
	
				// Describe expected parameters
				array( '--verbose|-v',     '(optional) turn on verbose mode'        ),
		);
	}	
}
