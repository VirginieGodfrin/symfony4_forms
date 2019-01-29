<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

// configuresthe annotation system: 
// 		@Target tells the annotation system where your annotation is allowed to be used. 
// 		This says that it's okay for this annotation to be used above a property, 
// 		above a method or even inside of another annotation
// 	If you need to be able to configure more things on your annotation, just create more public properties on UniqueUser. 
// 	Any properties on this class can be set or overridden as options when using the annotation.

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class UniqueUser extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
   	public $message = 'The email {{ value }} is already registered';
}
