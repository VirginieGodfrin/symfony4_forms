<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;

// Form classes are usually called form "types", 
// and the only rule is that they must extend a class called AbstractType . 

class ArticleFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options) {
		// build the form
		$builder 
			// the add() method has three arguments: the field name, the field type and some options.
			->add('title', TextType::class, [
				'help' => 'Choose something catchy!'
			]) 
			->add('content')
			// To set an option on the publishedAt field, pass null as the second argument and set up the array as the third. 
			// Null just tells Symfony to continue "guessing" this field type. 
			// Basically, I'm being lazy: we could pass DateTimeType::class ... but we don't need to!
			->add('publishedAt', null, [
				'widget' => 'single_text'
			])
			// In reality, each field type - like DateTimeType - has two superpowers. 
			// First, it determines how the field is rendered. Like, an input type="text" field or, a bunch of drop-downs, 
			// or a fancy datetime-local input. 
			// Second... and this is the real superpower, a field type is able to transform the data to and from your object 
			// and the form. This is called "data transformation".
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