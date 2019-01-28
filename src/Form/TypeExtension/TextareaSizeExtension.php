<?php

namespace App\Form\TypeExtension;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


// we want to set the row option the same for every textarea across our entire app !
// Creating the Form Type Extension
class TextareaSizeExtension implements FormTypeExtensionInterface
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }
    // we want to modify the view variables.
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    	$view->vars['attr']['rows'] = $options['rows'];
    }
    // in finishView it adds a few variables to help render an _token CSRF token field.
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
		$resolver->setDefaults([ 
			'rows' => 5
		]);
    }

    // But then, how does Symfony know which form type we want to extend in Symfony 4.2? 
    // The getExtendedType() method! 
    // Inside, return TextareaType::class. 
    // And yea, we also need to fill in this method in Symfony 4.1... it's a bit redundant,
    // which is why Symfony 4.2 will be so much cooler.
    public function getExtendedType()
    {
        return TextareaType::class;
    }
}