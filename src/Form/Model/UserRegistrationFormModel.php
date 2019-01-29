<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

// it is not possible to make UniqueEntity work on a class that is not an entity class. 
class UserRegistrationFormModel {

	/**
	 * @Assert\NotBlank(message="Please enter an email")
	 * @Assert\Email()
	 */
	public $email;

	/**
	 * @Assert\NotBlank(message="Choose a password!")
	 * @Assert\Length(min=5, minMessage="Come on, you can think of a password longer than that!") 
	 */
	public $plainPassword;

	/**
	 * @Assert\IsTrue(message="I know, it's silly, but you must agree to our terms.")
	 */
	public $agreeTerms;


}