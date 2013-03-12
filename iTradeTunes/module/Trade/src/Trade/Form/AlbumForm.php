<?php
// module/Trade/src/Trade/Form/AlbumForm.php:
namespace Trade\Form;

use Application\Form\AbstractApplicationForm;

class AlbumForm extends AbstractApplicationForm
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('album');
		$this->setAttribute('method', 'post');
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type'  => 'hidden',
				),
		));
		$this->add(array(
				'name' => 'artist',
				'attributes' => array(
						'type'  => 'text',
				),
				'options' => array(
						'label' => 'Artist',
				),
		));
		$this->add(array(
				'name' => 'title',
				'attributes' => array(
						'type'  => 'text',
				),
				'options' => array(
						'label' => 'Title',
				),
		));
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => 'Go',
						'id' => 'submitbutton',
				),
		));
	}
}