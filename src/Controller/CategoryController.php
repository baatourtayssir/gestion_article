<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    // public function home()
    // {
    //     //récupérer tous les articles de la table article de la BD
    //     // et les mettre dans le tableau $articles
    //     $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
    //     return $this->render('category/index.html.twig', ['category' => $category]);
    // }






    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('Category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $categoryRepository->save($category, true);
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Category/_form.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    // public function show(Category $category): Response
    // {
    //     return $this->render('category/Form/show.html.twig', [
    //         'category' => $category,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Category/index/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete')]

    public function delete(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        $response = new Response();
        $response->send();


        return $this->redirectToRoute('app_category_index');
        return $this->renderForm('Category/index/_delete_form.html.twig');
    }



    // public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
    //         $categoryRepository->remove($category, true);
    //     }

    //     return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    // }
}
