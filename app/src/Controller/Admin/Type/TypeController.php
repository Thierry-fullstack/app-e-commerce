<?php

namespace App\Controller\Admin\Type;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/type',name:'app_admin_type_')]
final class TypeController extends AbstractController
{
    #[Route('/list', name: 'list',methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/type/index.html.twig');
    }


}
