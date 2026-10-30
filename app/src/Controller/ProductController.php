<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/product',name: 'app_product_')]
final class ProductController extends AbstractController
{
    #[Route('/category/list/{id}', name: 'list',methods: ['GET'])]
    public function ShowProductsByCategory(Category $category, ProductRepository $productRepository,Request $request): Response
    {

    }

    #[Route('/show/article/{slug}',name: 'show_article',methods: ['GET'])]
    public function showArticleByCategory(Product $product):Response
    {

    }
}
