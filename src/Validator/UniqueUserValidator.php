<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UserRepository;

class UniqueUserValidator extends ConstraintValidator
{
	// create a query with dependency injection (UniqueUserValidator is a service)
	private $userRepository;

	public function __construct(UserRepository $userRepository) {
		$this->userRepository = $userRepository; 
	}

    public function validate($value, Constraint $constraint)
    {
    	$existingUser = $this->userRepository->findOneBy([ 
    		'email' => $value
		]);

		if (!$existingUser) { 
			return;
		}

    	// you can wipe off setParameter if you don't use value in uniqueUser.php
        /* @var $constraint App\Validator\UniqueUser */

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

    // One note: if this were an edit form where a user could change their email, this validator would need to make sure that the existing user wasn't actually just this user, if they submitted without changing their email. In that case, we would need $value to be the entire object so that we could use the id to be sure of this. To do that, you would need to change UniqueUser so that it lives above the class, instead of the property. You would also need to add an id property to UserRegistrationFormModel .
}
