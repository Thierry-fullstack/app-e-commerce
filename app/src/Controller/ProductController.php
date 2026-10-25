<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product',name: 'app_product_')]
final class ProductController extends AbstractController
{
    #[Route('/category/list/{id}', name: 'list',methods: ['GET'])]
    public function ShowProductByCategory(Category $category, ProductRepository $productRepository): Response
    {

        $products = $productRepository->findProductsByCategory($category);
        return $this->render('product/productsByCategory.html.twig',['products'=>$products,'category'=>$category]);
    }
}
