<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


// Creating the Custom Form Type
class UserSelectTextType extends AbstractType 
{
	// with getParent UserSelectTextType will work like textType
	public function getParent() {
		return TextType::class; 
	}
}