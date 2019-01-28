<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

// To get our autocomplete fully working, we need an API endpoint that returns a list of user information

class AdminUtilityController extends AbstractController
{

/**
 * @Route("/admin/utility/users", methods="GET", name="admin_utility_users")
 * @IsGranted("ROLE_ADMIN_ARTICLE")
 */
public function getUsersApi(UserRepository $userRepository, request $request)
{
	// create a repo function with the query parameter form the url  
    $users = $userRepository->findAllMatching($request->query->get('query'));
    //  we can serialize the group "main"
    return $this->json([
        'users' => $users
    ], 200,[],['groups'=>['main']]);

}

}