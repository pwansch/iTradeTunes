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

class Module implements ConsoleUsageProviderInterface
{
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
