<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Article;

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

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([ 
			// This is where you can set options that control how your form behaves
			'data_class' => Article::class
		]); 
	}
	// But, when you bind your form to a class, a special system - 
	// called the "form type guessing" system - 
	// tries to guess the proper "type" for each field.
	// And when the form submits, it notices the data_class and so creates a new Article() object for us. 
	// Then, it uses the setter methods to populate the data.

}