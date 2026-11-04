<?php

namespace App\Controller\Admin\Product;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\AddProductType;
use App\FormAjax\AjaxProductService;
use App\Repository\ProductRepository;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/produit', name: 'app_admin_product_')]
final class ProductController extends AbstractController
{
    public const DIR_PRODUCTS = 'product';

    /**
     * @param ProductRepository $productRepository
     * @return Response
     */
    #[Route('/list', name: 'list',methods: ['GET'])]
    public function index(ProductRepository $productRepository,Request $request,PaginatorInterface $paginator): Response
    {   try {
        $datas = $productRepository->findBy([], ['name' => 'DESC']);
        $products = $paginator->paginate($datas, $request->query->getInt('page', 1), 6);
    }catch (EntityNotFoundException $exception)
    {
        $this->addFlash('warning','Connection impossible : ' .$exception);
        return $this->redirectToRoute('app_admin_index');
    }
       return $this->render('admin/product/list.html.twig', ['products'=>$products]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @param PhotoService $photoService
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    #[Route('/ajout',name:'add',methods:['GET','POST'])]
    public function addProduct(RequestStack $requestStack,AjaxProductService $ajaxProductService,Request $request):Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = new Product();
        $request = $requestStack->getMainRequest();
        $form = $this->createForm(AddProductType::class,$product);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            if($form->isSubmitted()){
                return  $ajaxProductService->handleformData($form);
            }
        }
        return $this->render('admin/product/add_product.html.twig',['form'=>$form->createView()]);
    }
}
