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
        // handleRequest() 
        //      sees that this is a GET request,
        //      reads the data and executes Symfony's validation system. 
        //      If validation fails, then $form->isValid() returns false and we immediately render the template, 
        //      except that now errors will be displayed by each field with an error.
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var Article $article */
            $article = $form->getData();

            $em->persist($article); 
            $em->flush();

            // The addFlash() method is a shortcut to set a message in the session.
            // It's the perfect place to store temporary messages.
            $this->addFlash('success', 'Article Created! Knowledge is power!');

            return $this->redirectToRoute('admin_article_list');
        }
        return $this->render('article_admin/new.html.twig', [ 
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article", name="admin_article_list")
     */
    public function list(ArticleRepository $articleRepo) 
    {

        $articles = $articleRepo->findAll();

        return $this->render('article_admin/list.html.twig', [ 
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em)
    {
        // about $article the Article object:
        //      when Symfony renders the form, it calls the getter methods on that Article object and uses those values to
        //      fill in the values for the fields.
        //      when we submit, the form system calls the setter methods on that same Article object.
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $em->persist($article); 
            $em->flush();
            $this->addFlash('success', 'Article Updated! Wonderfull!');
            return $this->redirectToRoute('admin_article_edit', [ 
                'id' => $article->getId(),
            ]);
        }
        return $this->render('article_admin/edit.html.twig', [ 
            'articleForm' => $form->createView()
        ]);
    }
}
