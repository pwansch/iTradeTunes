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
		$this->setAttribute('method', 'post');

		// Add the elements to the form
		$id = new Element\Hidden('id');
		$first_name = new Element\Text('first_name');
		$first_name->setLabel($this->translate('First Name'))
		           ->setAttributes(array('size'  => '32'));
		$last_name = new Element\Text('last_name');		
		$last_name->setLabel($this->translate('Last Name'))
		          ->setAttributes(array('size'  => '32'));
		$email_address = new Element\Email('email_address');
		$email_address->setLabel($this->translate('Email Address'))
		      ->setAttributes(array('size'  => '96'));
		$email_address_private = new Element\Checkbox('email_address_private');
		$email_address_private->setLabel($this->translate('Email address private'));
		$email_address_private->setUseHiddenElement(true);
		$email_address_private->setCheckedValue("1");
		$email_address_private->setUncheckedValue("0");
		$password = new Element\Password('password');
		$password->setLabel($this->translate('Password'))
		         ->setAttributes(array('size'  => '30'));
		$about = new Element\Text('about');
		$about->setLabel($this->translate('About'))
	           ->setAttributes(array('size'  => '255'));
		$interests = new Element\Text('interests');
		$interests->setLabel($this->translate('Interests'))
		          ->setAttributes(array('size'  => '255'));
		$captchaImage = new Image(  array(
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
		//$captcha->setSession($this->_session);
		
		
		$captcha = new Element\Captcha('captcha');
		$captcha->setCaptcha($captchaImage);
		$captcha->setLabel($this->translate('Type text from the image above:'));

		$submit = new Element\Submit('submit');
		$submit->setValue($this->translate('Join'));
		
		// Add elements to form
		$this->add($id)
		     ->add($first_name)
		     ->add($last_name)
		     ->add($email_address)
		     ->add($email_address_private)
		     ->add($password)
		     ->add($about)
		     ->add($interests)
		     ->add($captcha)
		     ->add($submit);	
	}
}