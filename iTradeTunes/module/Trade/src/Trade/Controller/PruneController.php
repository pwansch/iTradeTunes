<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Trade\Controller;

use Application\Controller\AbstractApplicationController;
use Zend\Console\Request as ConsoleRequest;

class PruneController extends AbstractApplicationController
{
	public function pruneLogAction()
	{
		$request = $this->getRequest();

		// Make sure that we are running in a console and the user has not tricked our
		// application into running this action from a public web server
		if (!$request instanceof ConsoleRequest){
			throw new \RuntimeException('You can only use this action from a console.');
		}
		
		// Get flags from console
		$verbose = $request->getParam('v') | $request->getParam('verbose');		
	
		if ($verbose)
		{
			return "Logs have been pruned successfully.\n";
		} 
	}	
}