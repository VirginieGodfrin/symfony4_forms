<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


// Creating the Custom Form Type
class UserSelectTextType extends AbstractType
{
	private $userRepository;
	private $router;

	public function __construct(UserRepository $userRepository, RouterInterface $router) 
	{
		$this->userRepository = $userRepository;
		$this->router = $router;
	}
	// use the builderInterface methode (addModelTransformer) to transform the datatransformer
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// EmailToUserTransformer is not a service ! This object is not instantiated by Symfony's container.
		// So, we don't get our cool autowiring magic
		// Next, check out the build() method. See this array of $options ? That will now include
		// finder_callback , which will either be our default value, or some other callback if it was overridden.
		$builder->addModelTransformer(new EmailToUserTransformer(
			$this->userRepository,
			$options['finder_callback']
		));
	}
	// with getParent UserSelectTextType will work like textType
	public function getParent() 
	{
		return TextType::class; 
	}

	// configureOptions(): Defining Field Options / Default
	// configureOptions() is used to set some options on your... whole form
	// But when you're creating a custom field type: configureOptions() is used to set the options for that specific field.
	public function configureOptions(OptionsResolver $resolver) 
	{
		// Add a new option called finder_callback and give it a default value: 
		// a callback that accepts a UserRepository $userRepository argument and the value - 
		// which will be a string $email .
		// Inside return the normal $userRepository->findOneBy() with ['email' => $email] .
		$resolver->setDefaults([
			'invalid_message' => 'Hmm, user not found!',
			'finder_callback' => function(UserRepository $userRepository, string $email) {
                return $userRepository->findOneBy(['email' => $email]);
            },
            // add the class js-user-autocomplete to the input tag
            // attr is one of a few things that can be passed either as a view variable or also as a field option.
            'attr' => [
				'class' => 'js-user-autocomplete',
				'data-autocomplete-url' => $this->router->generate('admin_utility_users')
			]
		]); 
	}
	// the buildView 
	// Symfony creates a bunch of variables that are used in the form theme system.
	// Symfony calls this method on every field, and it is the place where these variables are created and can be changed.
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		// create the attr var
		$attr = $view->vars['attr'];
		// if class is set on $attr, use it, but add a space on the end. 
		// If there is no class yet, set this to be blank
		$class = isset($attr['class']) ? $attr['class'].' ' : '';
		// happend it the key
		$class .= 'js-user-autocomplete';

		// set the new class
		$attr['class'] = $class;
		// add the data-autocomplete-url attribute
		$attr['data-autocomplete-url'] = $this->router->generate('admin_utility_users');

		$view->vars['attr'] = $attr;
		// a good way to know our attributes will be rendered no matter what the user passes to the attr option.
	}

}