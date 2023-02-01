<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/article')]

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]

    public function home()
    {
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', ['articles' => $articles]);
    }


    // public function index(ArticleRepository $articleRepository): Response
    // {
    //     return $this->render('Article/index.html.twig', [
    //         'articles' => $articleRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if (!is_null($article->getImage())) {
            $file = $article->getImageFile();
            $fileName = md5(uniqid()) . 'blog-.' . $file->guessExtension();
            $file->move(
                $this->getParameter('app.path.product_images'),
                $fileName
            );
            $article->setImage($fileName);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();



            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Article/_form.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    // public function show(Article $article): Response
    // {
    //     return $this->render('article/Form/show.html.twig', [
    //         'article' => $article,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id)
    {
        $article = new Article();
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index');
        }

        return $this->render('article/index/edit.html.twig', ['form' => $form->createView()]);
    }















    // public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    // {
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $articleRepository->save($article, true);

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('Article/index/edit.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }




    #[Route('/{id}', name: 'app_article_delete')]
    public function delete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();


        return $this->redirectToRoute('app_article_index');
        return $this->renderForm('Article/index/_delete_form.html.twig');
    }
    // }


    // #[Route('/{id}', name: 'app_article_delete')]
    // public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
    //         $articleRepository->remove($article, true);
    //     }

    //     return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    // }
}
