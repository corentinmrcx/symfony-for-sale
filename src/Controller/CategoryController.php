<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1): Response
    {
        $query = $categoryRepository->queryAllAlphabetically();
        $pagination = $paginator->paginate(
            $query,
            $page,
            15
        );

        return $this->render('category/index.html.twig', [
            'categories' => $pagination,
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show')]
    public function show(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'advertisements' => $category->getAdvertisements(),
        ]);
    }
}
