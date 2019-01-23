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
			->add('author', EntityType::class, [
				'class' => User::class,
				// 'choice_label' => 'email',
				// choice label with callback
				'choice_label' => function(User $user) {
					return sprintf('(%d) %s', $user->getId(), $user->getEmail());
				},
				'placeholder' => 'Choose an author',
				// Normally, when you use the EntityType , you don't need to pass the choices option. 
				// Remember, if you look at ChoiceType , the choices option is how you specify which, ah, choices you want to show in the drop-down. 
				// But EntityType queries for the choices and basically sets this option for us.
				// To control that query, there's an option called query_builder . 
				// Or, you can do what I do: be less fancy and simply override the choices option entirely. 
				// Yep, you basically say:
				'choices' => $this->userRepository->findAllEmailAlphabetical(),
				// a way to validate fields, require validator
				'invalid_message' => 'Symfony is too smart for your hacking!'
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