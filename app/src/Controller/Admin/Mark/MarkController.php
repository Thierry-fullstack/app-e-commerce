<?php

namespace App\Controller\Admin\Mark;

use App\Entity\Mark;
use App\Form\MarkFormType;
use App\FormAjax\AjaxMarkService;
use App\Repository\MarkRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mark',name:'app_admin_mark_')]
final class MarkController extends AbstractController
{
    #[Route('/list', name: 'list',methods: ['GET'])]
    public function index(MarkRepository $markRepository): Response
    {
        return $this->render('mark/index.html.twig',['marks'=>$markRepository->findBy([],['slug'=>'ASC'])]);
    }

    #[Route('/add', name: 'add',methods: ['GET','POST'])]
    public function addMark(RequestStack $requestStack,AjaxMarkService $markService,Request $request,
                            MarkRepository $markRepository,PaginatorInterface $paginator): Response
    {
        $datas = $markRepository->findBy([],['slug'=>'ASC']);
        $marks = $paginator->paginate(
            $datas,
            $request->query->getInt('page',1),
            5
        );
        $mark = new Mark();
        $request = $requestStack->getMainRequest();
        $form = $this->createForm(MarkFormType::class,$mark);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            if($form->isSubmitted()) {
                return $markService->handleFormData($form);
            }
        }
        return $this->render('mark/add.html.twig',['form'=>$form->createView(),'marks'=>$marks]);
    }
}
