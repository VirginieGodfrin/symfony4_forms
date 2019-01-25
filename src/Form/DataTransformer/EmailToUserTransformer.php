<?php  
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\Exception\TransformationFailedException;

// there is no data transformer method ! So when the form is submited the form system call setAuthor and try to pass
// it the string first name ...To fix this, our field needs a data transformer: something that's capable of taking
// the User object and rendering its email field. 
// And on submit, transforming that email string back into a User object.
// The only rule for a data transformer is that it needs to implement a DataTransformerInterface 


class EmailToUserTransformer implements DataTransformerInterface
{
	private $userRepository;
	public function __construct(UserRepository $userRepository) 
	{
		$this->userRepository = $userRepository; 
	}

	public function transform($value)
	{
		// the value is our User object.
		// This method is called. when the form is rendering
		// we want to transform the author property (user object) into an into a representation 
		// that can be used for the form field email string
		// dd('transform', $value);

		if (null === $value) { 
			return '';
		}

		if (!$value instanceof User) {
			throw new \LogicException('The UserSelectTextType can only be used with User objects');
		}

		return $value->getEmail();
	}
	
	// this method is called when the form is submitted
	// his job is to query for a User object and return it
	public function reverseTransform($value)
	{
		// dd('reverse transform', $value);
		if(!$value){
			return;
		}
		$user = $this->userRepository
			->findOneBy(['email' => $value]);

		if (!$user) {
			throw new TransformationFailedException(sprintf('No user found with email "%s"', $value));
		}

		return $user;
	}

}