<?php

namespace App\Controller\Admin\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/admin/product/product', name: 'app_admin_product_product')]
    public function index(): Response
    {
        return $this->render('admin/product/product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
