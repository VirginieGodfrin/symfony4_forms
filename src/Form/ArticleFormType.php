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
		// we can see data in edit form and not in new form
		// dd($options);

		// if $options['data'] is not null and if it's exist , article = $options['data']
		// but if $options['data'] do not exist set it to null
		$article = $options['data'] ?? null;
		// dd($article);
		//  check if $article exist and if it got an id
		$isEdit = $article && $article->getId();

		$builder 
			->add('title', TextType::class, [
				'help' => 'Choose something catchy!'
			]) 
			->add('content')
			->add('author', UserSelectTextType::class,[
				// But it also now ignores any submitted data for this field. 
				// So, if a nasty user removed the disabled attribute and updated the field, 
				// meh - no problem - our form will ignore that submitted data.
				'disabled' => $isEdit // true or false
			])	
		;
		// in edit form $options['include_published_at'] is set to true
		// in new form $options['include_published_at'] is set to false 
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