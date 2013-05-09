<?php

namespace Trade\Form;

use Zend\Captcha;
use Zend\Captcha\Image;
use Zend\Form\Element;
use Application\Form\AbstractApplicationForm;

class MemberForm extends AbstractApplicationForm
{
	public function init()
	{
		// Set form attributes
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('id', 'memberForm');

		// Add the elements to the form, labels will automatically get translated
		$id = new Element\Hidden('id');
		$first_name = new Element\Text('first_name');
		$first_name->setLabel('First Name')
				   ->setLabelAttributes(array('class'  => 'control-label'))
		           ->setAttributes(array('id' => 'first_name'));

		$last_name = new Element\Text('last_name');		
		$last_name->setLabel('Last Name')
				  ->setLabelAttributes(array('class'  => 'control-label'))
				  ->setAttributes(array('id' => 'last_name'));
		
		$email_address = new Element\Email('email_address');
		$email_address->setLabel('Email Address')
		              ->setLabelAttributes(array('class'  => 'control-label'))
		              ->setAttributes(array('id' => 'email_address'));
		
		$email_address_private = new Element\Checkbox('email_address_private');
		$email_address_private->setLabel('Private')
		                      ->setLabelAttributes(array('class'  => 'checkbox'))
							  ->setAttributes(array('id' => 'email_address_private'))
		                      ->setUseHiddenElement(true)
		                      ->setCheckedValue("1")
		                      ->setUncheckedValue("0");
		
		$password = new Element\Password('password');
		$password->setLabel('Password')
		         ->setLabelAttributes(array('class'  => 'control-label'))
		         ->setAttributes(array('id' => 'password'));

		$password_repeat = new Element\Password('password_repeat');
		$password_repeat->setLabel('Repeat Password')
		                ->setLabelAttributes(array('class'  => 'control-label'))
		                ->setAttributes(array('id' => 'password_repeat'));
		
		$about = new Element\Text('about');
		$about->setLabel('About Me')
		       ->setLabelAttributes(array('class'  => 'control-label'))
		       ->setAttributes(array('id' => 'about'));

		$interests = new Element\Text('interests');
		$interests->setLabel('Interests')
		          ->setLabelAttributes(array('class'  => 'control-label'))
		          ->setAttributes(array('id' => 'interests'));

		$captchaImage = new Image(array(
				'font' => './fonts/arial.ttf',
				'fontSize' => 32,
				'wordLen' => 6,
				'width' => 250,
				'height' => 100,
				'timeout' => 300,
				'expiration' => 500,
				'gcFreq' => 100,
				'dotNoiseLevel' => 40,
				'lineNoiseLevel' => 3)
		);
		$captchaImage->setImgDir('./userimages/captcha');
		$captchaImage->setImgUrl('/userimages/captcha');
		$captchaImage->setName('member_captcha');
		$captchaImage->setSession($this->getSession());
		$captcha = new Element\Captcha('captcha');
		$captcha->setCaptcha($captchaImage);
		$captcha->setLabel('Type Text From Above')
		        ->setLabelAttributes(array('class'  => 'control-label'))
		        ->setAttributes(array('id' => 'captcha'));

		$csrf = new Element\Csrf('security');		

		$submit = new Element\Submit('submit');
		$submit->setAttributes(array('class' => 'btn'));
		
		
		// Add elements to form
		$this->add($id)
		     ->add($first_name)
		     ->add($last_name)
		     ->add($email_address)
		     ->add($email_address_private)
		     ->add($password)
		     ->add($password_repeat)
		     ->add($about)
		     ->add($interests)
		     ->add($captcha)
		     ->add($csrf)
		     ->add($submit);	
	}
}