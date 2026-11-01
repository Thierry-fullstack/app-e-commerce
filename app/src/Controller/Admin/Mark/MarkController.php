<?php

namespace App\Controller\Admin\Mark;

use App\Entity\Mark;
use App\Form\MarkFormType;
use App\FormAjax\AjaxMarkService;
use App\Repository\MarkRepository;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mark',name:'app_admin_mark_')]
final class MarkController extends AbstractController
{

    /**
     * @param MarkRepository $markRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/list', name: 'list',methods: ['GET'])]
    public function index(MarkRepository $markRepository,PaginatorInterface $paginator, Request $request): Response
    {
        try {
            $datas = $markRepository->findBy([], ['slug' => 'ASC']);
            $num = 0 ? ceil(count($datas)/ 5): 1;
            $marks = $paginator->paginate($datas, $request->query->getInt('page', $num), 5);
            return $this->render('mark/index.html.twig', ['marks' => $marks]);
        }catch(EntityNotFoundException $exception){
            $this->addFlash('warning','Connection impossible :' .$exception );
            return $this->redirectToRoute('app_admin_index');
        }
    }

    /**
     * @param RequestStack $requestStack
     * @param AjaxMarkService $markService
     * @param Request $request
     * @param MarkRepository $markRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/add', name: 'add',methods: ['GET','POST'])]
    public function addMark(RequestStack $requestStack,AjaxMarkService $markService,Request $request,
                            MarkRepository $markRepository,PaginatorInterface $paginator): Response
    {
        try {
            $datas = $markRepository->findBy([], ['slug' => 'ASC']);
            $num = 0 ? ceil(count($datas)/ 5): 1;
            $marks = $paginator->paginate(
                $datas,
                $request->query->getInt('page', $num), 5);
        }catch(EntityNotFoundException $exception){
            $this->addFlash('warning','Connection impossible :' .$exception );
            return $this->redirectToRoute('app_admin_index');
        }
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
