<?php
// module/Trade/src/Trade/Controller/AlbumRestController.php:
namespace Trade\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class AlbumRestController extends AbstractRestfulController
{
	public function getList()
	{
		return new JsonModel(array(
				'data' => 'ok1',
		));		
	}
	
	public function get($id)
	{
		return new JsonModel(array(
				'data' => 'ok2',
		));		
	}
	
	public function create($data)
	{
	}
	
	public function update($id, $data)
	{
	}
	
	public function delete($id)
	{
	}
}