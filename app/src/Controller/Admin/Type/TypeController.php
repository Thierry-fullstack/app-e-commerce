<?php

namespace App\Controller\Admin\Type;

use App\Entity\Type;
use App\Form\TypeFormType;
use App\FormAjax\AjaxTypeService;
use App\Repository\TypeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/type',name:'app_admin_type_')]
final class TypeController extends AbstractController
{
    #[Route('/list', name: 'list',methods: ['GET'])]
    public function index(TypeRepository $typeRepository,PaginatorInterface $paginator,Request $request): Response
    {
        try {
            $datas = $typeRepository->findBy([], ['slug' => 'ASC']);
            $types = $paginator->paginate($datas, $request->query->getInt('page', 1), 5);
            return $this->render('admin/type/index.html.twig', ['types' => $types]);
        }catch(EntityNotFoundException $exception){
            $this->addFlash('warning','Connection impossible :' .$exception );
            return $this->redirectToRoute('app_admin_index');
        }
    }
    #[Route('/add', name: 'add',methods: ['GET','POST'])]
    public function addType(RequestStack $requestStack,AjaxTypeService $typeService,Request $request,
    TypeRepository $typeRepository,PaginatorInterface $paginator): Response
    {
        try{
            $datas = $typeRepository->findBy([],['slug'=>'ASC']);
            //$num = ceil(count($datas)/ 5);

            $types = $paginator->paginate($datas,$request->query->getInt('page',1),5);
        }catch (EnvNotFoundException $exception){
            $this->addFlash('warning','Connection impossible :' .$exception );
            return $this->redirectToRoute('app_admin_index');
        }
        $type = new Type();
        $request = $requestStack->getMainRequest();
        $form = $this->createForm(TypeFormType::class,$type);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            if($form->isSubmitted()){
                return $typeService->handleFormData($form);
            }
        }

        return $this->render('admin/type/add.html.twig',['form'=>$form->createView(),'types'=>$types]);
    }

}
