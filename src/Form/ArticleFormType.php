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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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

		$article = $options['data'] ?? null;

		$isEdit = $article && $article->getId();

		$builder 
			->add('title', TextType::class, [
				'help' => 'Choose something catchy!'
			]) 
			->add('content')
			->add('author', UserSelectTextType::class,[
				'disabled' => $isEdit
			])
			->add('location', ChoiceType::class, [
				'placeholder' => 'Choose a location',
				'choices'  => [
        			'The Solar System' => "solar_system",
        			'Near a star' => "star",
        			'Interstellar Space' => "interstellar_space",
        			// "the value in the form" => "the value in the db"
    			],
    			// because we pass the field type in second argument
    			'required' => false, 
			])
			->add('specificLocationName', ChoiceType::class, [
				'placeholder' => 'Where exactly?',
				'choices'  => [
        			'TODO' => 'TODO'
    			],
    			'required' => false, 
			])
		;

		if ($options['include_published_at']) { 
			$builder->add('publishedAt', null, [
				'widget' => 'single_text', 
			]);
		}
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([ 
			// Now, up in buildForm() , the $options array will always have an include_published_at key
			'data_class' => Article::class,
			'include_published_at' => false,
		]); 
	}
	// But, when you bind your form to a class, a special system - 
	// called the "form type guessing" system - 
	// tries to guess the proper "type" for each field.
	// And when the form submits, it notices the data_class and so creates a new Article() object for us. 
	// Then, it uses the setter methods to populate the data.

}