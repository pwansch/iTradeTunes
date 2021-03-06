<?php
// module/Trade/src/Trade/Controller/AlbumController.php:
namespace Trade\Controller;

use Application\Controller\AbstractApplicationController;
use Zend\View\Model\ViewModel;
use Trade\Model\Album;          // <-- Add this import
use Trade\Form\AlbumForm;       // <-- Add this import

class AlbumController extends AbstractApplicationController
{
	protected $albumTable;
	
    public function indexAction()
    {
    	// Get the page number from the request
    	$page = $this->params()->fromRoute('page');
    	
    	// Get the paginator from the table object
    	$paginator = $this->getAlbumTable()->getPaginator($page, 2, 1);
    	
        return new ViewModel(array(
        	'paginator' => $paginator,
        ));
    }

    // Add content to this method:
    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->beginTransaction();
                $this->getAlbumTable()->saveAlbum($album);
                $this->commitTransaction();

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }
    
public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }
        $album = $this->getAlbumTable()->getAlbum($id);

        $form  = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }
    	
	public function getAlbumTable()
	{
		if (!$this->albumTable) {
			$sm = $this->getServiceLocator();
			$this->albumTable = $sm->get('Trade\Model\AlbumTable');
		}
		return $this->albumTable;
	}	
}