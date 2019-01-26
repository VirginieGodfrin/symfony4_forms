<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

// To get our autocomplete fully working, we need an API endpoint that returns a list of user information

class AdminUtilityController extends AbstractController
{

/**
 * @Route("/admin/utility/users", methods="GET")
 * @IsGranted("ROLE_ADMIN_ARTICLE")
 */
public function getUsersApi(UserRepository $userRepository)
{
    // The job of this endpoint is pretty simple: return an array of User objects as JSON:
    $users = $userRepository->findAllEmailAlphabetical();
    //  we can serialize the group "main"
    return $this->json([
        'users' => $users
    ], 200,[],['groups'=>['main']]);

}

}