<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\FormAjax\AjaxCategoryService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/category',name:'app_admin_category_')]
final class CategoryController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(CategoryRepository $categoryRepository,PaginatorInterface $paginator,Request $request): Response
    {
        try{
            $datas = $categoryRepository->findBy([],['slug'=>'ASC']);
            $categories = $paginator->paginate($datas,$request->query->getInt('page',1),5);
            return $this->render('admin/category/index.html.twig', [
                'categories'=>$categories]);
        }catch (EntityNotFoundException $exception){
            $this->addFlash('warning','Connection impossible : ' . $exception);
            return $this->redirectToRoute('app_admin_index');
        }
    }

    #[Route('/add', name: 'add',methods: ['GET','POST'])]
    public function addCategory(RequestStack $requestStack,AjaxCategoryService $categoryService,Request $request,
    CategoryRepository $categoryRepository,PaginatorInterface $paginator): Response
    {
        try{
            $datas = $categoryRepository->findBy([],['slug'=>'ASC']);
            $num = 0 ? ceil(count($datas) / 5 ) : 1;
            $categories = $paginator->paginate($datas,$request->query->getInt('page',$num),5);
        }catch(EntityNotFoundException $exception){
            $this->addFlash('warning','Connection impossible ' .$exception);
            return $this->redirectToRoute('app_admin_index');
        }

        $category = new Category();
        $request = $requestStack->getMainRequest();
        $form = $this->createForm(CategoryFormType::class,$category);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            if($form->isSubmitted()){
                return $categoryService->handleFormData($form);
            }
        }
        return $this->render('admin/category/add.html.twig', [
            'form'=>$form->createView(),'categories'=>$categories ]);
    }
}
