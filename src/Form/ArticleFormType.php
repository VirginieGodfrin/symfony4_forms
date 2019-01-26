<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Repository\UserRepository;

// Form classes are usually called form "types", 
// and the only rule is that they must extend a class called AbstractType . 

class ArticleFormType extends AbstractType{

	// Form types are services! So we can use our favorite pattern: dependency injection.
	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder 
			// the add() method has three arguments: the field name, the field type and some options.
			->add('title', TextType::class, [
				'help' => 'Choose something catchy!'
			]) 
			->add('content')
			->add('publishedAt', null, [
				'widget' => 'single_text'
			])
			// UserSelectTextType::class render a text field filled with the firstName (user to_string method) of the current author.
			//	Finder callback resume:  In ArticleFormType , when we use UserSelectTextType , 
			// we can pass a finder_callback option if we need to do a custom query. 
			// If we did that, it would override the default value and, 
			// when we instantiate EmailToUserTransformer , the second argument would be the callback 
			// that we passed from ArticleFormType .
			->add('author', UserSelectTextType::class)

			
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