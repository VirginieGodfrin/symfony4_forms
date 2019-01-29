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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

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

		/** @var Article|null $article */
		$article = $options['data'] ?? null;

		$isEdit = $article && $article->getId();
		// if $article is an object, then $article->getLocation() , otherwise, null 
		$location = $article ? $article->getLocation() : null;

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
		;

		if ($location) {
			$builder->add('specificLocationName', ChoiceType::class, [
				'placeholder' => 'Where exactly?',
				'choices' => $this->getLocationNameChoices($location), 
				'required' => false,
			]); 
		}

		if ($options['include_published_at']) { 
			$builder->add('publishedAt', null, [
				'widget' => 'single_text', 
			]);
		}

		// the Form Event 
		// There's a Form object on top and then each individual field below is itself a full Form object
		// When we call $builder->add() , that creates another "form builder" object for that field, 
		// and you can fetch it later by saying $builder->get() .
		// The FormEvent contains the raw, submitted data and the entire Form object for this one field.
		$builder->get('location')->addEventListener( 
			FormEvents::POST_SUBMIT, 
			function(FormEvent $event) {
				// in this callback call setupSpecificLocationNameField
				// dd($event); 
				$form = $event->getForm();
				$this->setupSpecificLocationNameField(
					// we want to pass the top level Form object that we can add or remove the specificLocationName field
					$form->getParent(), 
					$form->getData()
				);
			}
		);
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([ 
			// Now, up in buildForm() , the $options array will always have an include_published_at key
			'data_class' => Article::class,
			'include_published_at' => false,
		]); 
	}

	private function getLocationNameChoices(string $location)
	{
		$planets = [
			'Mercury',
			'Venus',
			'Earth',
			'Mars',
			'Jupiter',
			'Saturn',
			'Uranus',
			'Neptune',
		];

		$stars = [
			'Polaris',
			'Sirius',
			'Alpha Centauari A',
			'Alpha Centauari B',
			'Betelgeuse',
			'Rigel',
			'Other'
		];

		$locationNameChoices = [
			'solar_system' => array_combine($planets, $planets),
			'star' => array_combine($stars, $stars),
			'interstellar_space' => null,
		];

		return $locationNameChoices[$location];
	}

	private function setupSpecificLocationNameField(FormInterface $form, ?string $location){
		// if location is null remove specificLocationName field and return
		if (null === $location) { 
			$form->remove('specificLocationName');
			return; 
		}

		//  This is needed for when the user selects "Interstellar Space":
		//  that doesn't have any specific location name choices, and so we don't want that field at all.
		$choices = $this->getLocationNameChoices($location);

		if (null === $choices) {
			$form->remove('specificLocationName'); 
			return;
		}

		// Finally, we do want the specificLocationName field, but we want to use our new choices.
		$form->add('specificLocationName', ChoiceType::class, [ 
			'placeholder' => 'Where exactly?',
			'choices' => $choices,
			'required' => false,
			]);
		}
}