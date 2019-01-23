<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

// Form classes are usually called form "types", 
// and the only rule is that they must extend a class called AbstractType . 

class ArticleFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options) {
		// build the form
		$builder 
			->add('title') 
			->add('content')
		;
	}
}