<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product',name: 'app_product_')]
final class ProductController extends AbstractController
{
    #[Route('/category/list/{id}', name: 'list',methods: ['GET'])]
    public function ShowProductsByCategory(Category $category, ProductRepository $productRepository): Response
    {
        /* securise */
        $products = $productRepository->findProductsByCategory($category);
        return $this->render('product/productsByCategory.html.twig',['products'=>$products,'category'=>$category]);
    }

    #[Route('/show/article/{slug}',name: 'show_article',methods: ['GET'])]
    public function showArticleByCategory(Product $product):Response
    {
        /* securise */
        return $this->render('product/show_product.html.twig',['produit'=>$product]);
    }
}
