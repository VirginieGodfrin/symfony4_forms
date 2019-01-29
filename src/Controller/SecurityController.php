<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserRegistrationFormType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator)
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UserRegistrationFormModel $userModel */
            $userModel = $form->getData();
            // When you use a model class, the downside is that you need to do a bit more work to transfer the data 
            // from our model object into the entity object - or objects - that actually need it. 
            // That's why these model classes are often called "data transfer objects": 
            // they just hold data and help transfer it between systems: the form system and our entity classes.
            $user = new User();
            $user->setEmail($userModel->email); // the UserRegistrationFormModel proprieties are publics

            $user->setPassword($passwordEncoder->encodePassword( 
                $user,
                $userModel->plainPassword 
            ));

            if (true === $userModel->agreeTerms) {
                $user->agreeToTerms();
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }
        return $this->render('security/register.html.twig', [ 
            'registrationForm' => $form->createView(),
        ]);
    }
}
