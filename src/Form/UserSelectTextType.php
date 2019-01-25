<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;


// Creating the Custom Form Type
class UserSelectTextType extends AbstractType
{
	private $userRepository;

	public function __construct(UserRepository $userRepository) 
	{
		$this->userRepository = $userRepository; 
	}
	// use the builderInterface methode (addModelTransformer) to transform the datatransformer
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// EmailToUserTransformer is not a service ! This object is not instantiated by Symfony's container. So, we don't get our cool autowiring magic
		$builder->addModelTransformer(new EmailToUserTransformer(
			$this->userRepository
		));
	}
	// with getParent UserSelectTextType will work like textType
	public function getParent() {
		return TextType::class; 
	}

}