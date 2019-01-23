<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;

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
			->add('publishedAt', null, [
				'widget' => 'single_text'
			])
			->add('author', EntityType::class, [
				'class' => User::class,
				// 'choice_label' => 'email',
				// choice label with callback
				'choice_label' => function(User $user) {
					return sprintf('(%d) %s', $user->getId(), $user->getEmail());
				},
				'placeholder' => 'Choose an author',
			])
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