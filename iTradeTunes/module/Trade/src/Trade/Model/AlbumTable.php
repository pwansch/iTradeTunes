<?php
namespace Trade\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class AlbumTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function getPaginator($currentPageNumber = 1, $itemCountPerPage = 10, $pageRange = 1)
	{
		// Prepare where clause for paginator
        $albumListWhereClause = 'id > 0';
        $orderByClause = 'id desc';
        
        // Build album select statement
	    $select = new Select();
	    $select->from(array('a' => 'album'), array('id' => 'id', 'artist' => 'artist', 'title' => 'title'))->where($albumListWhereClause)->order($orderByClause);

	    // Create paginator
	    $adapter = new DbSelect($select, $this->tableGateway->getAdapter());
	    $paginator = new Paginator($adapter);
        $paginator->setItemCountPerPage($itemCountPerPage);
	    $paginator->setPageRange($pageRange);
        $paginator->setCurrentPageNumber($currentPageNumber);
        return $paginator;
    }
   	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getAlbum($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveAlbum(Album $album)
	{
		$data = array(
				'artist' => $album->artist,
				'title'  => $album->title,
		);
		$id = (int)$album->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getAlbum($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}

	public function deleteAlbum($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}