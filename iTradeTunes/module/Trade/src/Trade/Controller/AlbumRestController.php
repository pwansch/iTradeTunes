<?php
// module/Trade/src/Trade/Controller/AlbumRestController.php:
namespace Trade\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class AlbumRestController extends AbstractRestfulController
{
	// curl -i -H "Accept: application/json" http://itradetunes.localhost/album-rest
	public function getList()
	{
		return new JsonModel(array(
			'function' => 'getList',
		));		
	}
	
	// curl -i -H "Accept: application/json" http://zf2-tutorial.localhost/album-rest/1
	public function get($id)
	{
		return new JsonModel(array(
				'function' => 'get',
		));		
	}
	
	public function create($data)
	{
		return new JsonModel(array(
				'function' => 'create',
		));				
	}
	
	public function update($id, $data)
	{
		return new JsonModel(array(
				'function' => 'update',
		));
	}
	
	public function delete($id)
	{
		return new JsonModel(array(
				'function' => 'delete',
		));
	}
}