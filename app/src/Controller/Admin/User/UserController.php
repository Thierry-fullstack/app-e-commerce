<?php

namespace App\Controller\Admin\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user',name:'app_admin_user_')]
final class UserController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(UserRepository $userRepository,Request $request, PaginatorInterface $paginator): Response
    {
        try{
            $datas = $userRepository->findBy([],['id'=>'ASC']);
            $users = $paginator->paginate($datas,$request->query->getInt('page',1),10);
        }catch(EntityNotFoundException $exception){
            $this->addFlash('warning','Connection impossible : ' .$exception);
            return $this->redirectToRoute('app_admin_index');
        }
        return $this->render('admin/user/index.html.twig',['users'=>$users]);
    }

    #[Route('/edit/', name: 'edit')]
    public function editUser(): Response
    {
        return $this->render('admin/user/edit.html.twig');
    }
}
