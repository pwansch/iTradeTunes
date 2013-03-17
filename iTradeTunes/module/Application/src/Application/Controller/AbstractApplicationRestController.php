<?php
// module/Trade/src/Trade/Controller/AlbumRestController.php:
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\EventManager\EventManagerInterface;

abstract class AbstractApplicationRestController extends AbstractRestfulController
{
	protected $allowedCollectionMethods = array(
			'GET',
			'POST',
	);
	
	protected $allowedResourceMethods = array(
			'GET',
			'PATCH',
			'PUT',
			'DELETE',
	);

	public function setEventManager(EventManagerInterface $events)
	{
		parent::setEventManager($events);
		$events->attach('dispatch', array($this, 'checkOptions'), 10);
	}
	
	public function checkOptions($e)
	{
		$matches  = $e->getRouteMatch();
		$response = $e->getResponse();
		$request  = $e->getRequest();
		$method   = $request->getMethod();
	
		// Test if we matched an individual resource, and then test
		// if we allow the particular request method
		if ($matches->getParam('id', false)) {
			if (!in_array($method, $this->allowedResourceMethods)) {
				$response->setStatusCode(405);
				return $response;
			}
			return;
		}
	
		// We matched a collection; test if we allow the particular request
		// method
		if (!in_array($method, $this->allowedCollectionMethods)) {
			$response->setStatusCode(405);
			return $response;
		}
	}
		
	public function options()
	{
		$response = $this->getResponse();
		$headers  = $response->getHeaders();
	
		// Check if an identifier from the route is present
		if ($this->params()->fromRoute('id', false)) {
			// Allow viewing, partial updating, replacement, and deletion
			// on individual items
			$headers->addHeaderLine('Allow', implode(',', array(
					'GET',
					'PATCH',
					'PUT',
					'DELETE',
			)));
			return $response;
		}
	
		// Allow only retrieval and creation on collections
		$headers->addHeaderLine('Allow', implode(',', array(
				'GET',
				'POST',
		)));
		return $response;
	}	
}