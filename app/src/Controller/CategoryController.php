<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category',name: 'app_category_')]
final class CategoryController extends AbstractController
{

    /**
     * @param Category $category
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/list/{slug}', name: 'list_products',methods: ['GET'])]
    public function getProductsByCategory(Category $category,CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findSubCategoryByCategory($category);

            return $this->render('category/index.html.twig', [
                'categories'=> $categories,'subCategorie'=>$category]);
    }
}
