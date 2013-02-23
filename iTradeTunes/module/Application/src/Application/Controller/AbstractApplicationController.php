<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractApplicationController extends AbstractActionController
{
	protected $dbAdapter;
	
	public function getDbAdapter()
	{
		if (!$this->dbAdapter) {
			$sm = $this->getServiceLocator();
			$this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		}
		return $this->dbAdapter;
	}

	public function beginTransaction()
	{
		$this->getDbAdapter()->getDriver()->getConnection()->beginTransaction();
	}
	
	public function commitTransaction()
	{
		$this->getDbAdapter()->getDriver()->getConnection()->commit();
	}
	
	public function rollbackTransaction()
	{
		$this->getDbAdapter()->getDriver()->getConnection()->rollback();
	}
	
	public function disableForeignKeysCheck()
	{
		$this->getDbAdapter()->getDriver()->getConnection()->exec('SET foreign_key_checks = 0');
	}
	
	public function enableForeignKeysCheck()
	{
		$this->getDbAdapter()->getDriver()->getConnection()->exec('SET foreign_key_checks = 1');
	}	
}