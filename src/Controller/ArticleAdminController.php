<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        // the form object
        $form = $this->createForm(ArticleFormType::class);
        // By default, handleRequest() only processes the data when this is a POST request. 
        // So, when the form is being submitted. When the form is originally loaded, 
        // handleRequest() sees that this is a GET request,
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // dd($form->getData());
            $data = $form->getData();
            $article = new Article(); 
            $article->setTitle($data['title']); 
            $article->setContent($data['content']); 
            $article->setAuthor($this->getUser());

            $em->persist($article); 
            $em->flush();

            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('article_admin/new.html.twig', [ 
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article") 
     */
    public function list(ArticleRepository $articleRepo) 
    {

        $articles = $articleRepo->findAll();

        return $this->render('article_admin/list.html.twig', [ 
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article)
    {
        dd($article);
    }
}
