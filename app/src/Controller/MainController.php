<?php

namespace App\Controller;


use App\Entity\Type;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\TypeRepository;
use App\Service\IntraController;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    /**
     * @param IntraController $intraController
     * @param JwtService $jwtService
     * @param MessageBusInterface $messageBus
     * @param Session $session
     * @return Response
     * @throws ExceptionInterface
     */
    #[Route('/', name: 'app_main')]
    public function index(IntraController $intraController,JwtService $jwtService,MessageBusInterface $messageBus,
    Session $session): Response
    {
        if($this->getUser()){
            $user = $this->getUser();
            if($user->getStep() ==='B'){
                return $this->redirectToRoute('app_inscription_client');
            }
            if($user->getStep() ==='A'){
                $this->addFlash('warning', 'Votre compte n\'est pas encore activé. Veuillez vérifier votre boîte de réception pour l\'email de confirmation.');
                $intraController->emailValidate($user,$jwtService,$messageBus,User::DESTINATION,User::SUBJECT,User::TEMPLATE);
            }
            if($user->getStep() ==='C'){
                $sessionId = $session->getId();
                if($sessionId !== $this->getUser()->getSession()){
                    return $this->redirectToRoute('profile_app_register_verified');
                }
            }
        }
        return $this->render('main/index.html.twig');
    }

    #[Route('/type/list/{name}',name:'app_main_list_types',methods:['GET'])]
    public  function listByType(Type $type, TypeRepository $typeRepository):Response
    {
        $types = $typeRepository->findTypesByType($type->getName());
        return $this->render('main/list_by_type.html.twig',['types'=>$types]);
    }

    #[Route('/product/list/{id}',name:'app_main_list_categories',methods:['GET'])]
    public  function listByProduct(Type $type, CategoryRepository $categoryRepository):Response
    {
        $categories = $categoryRepository->findCategoriesByType($type->getId());

        return $this->render('main/list_categories_by_type.html.twig',['categories'=>$categories]);
    }
}
