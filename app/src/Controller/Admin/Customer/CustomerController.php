<?php

namespace App\Controller\Admin\Customer;

use App\Form\VerifyNumberType;
use App\Repository\UserRepository;
use App\Service\IntraController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'profile_')]
final class CustomerController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @param IntraController $intraController
     * @param MessageBusInterface $messageBus
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ExceptionInterface
     */
    #[Route('register/verified',name: 'app_register_verified')]
    public function verifyDevice(UserRepository $userRepository, IntraController $intraController, MessageBusInterface $messageBus,
       EntityManagerInterface $entityManager):Response
    {
        if($this->getUser()){
            $user = $userRepository->find($this->getUser());
            $number = mt_rand(100001,999999);
            $user->setResetNumber($number);
            $this->addFlash('warning','Un courriel de vérification vous a été envoyé.');
            $intraController->emailSimple($user,$messageBus,['user'=>$user,'number'=>$number]);
            $entityManager->flush();
        }
        return $this->redirectToRoute('profile_app_verified_user');
    }

    #[Route('register/user',name:'app_verified_user',methods: ['POST','GET'])]
    public function verifyUser(EntityManagerInterface $entityManager,Request $request, SessionInterface $session):Response
    {
        $now = new \DateTimeImmutable();
        $timer = $now->getTimestamp();
        // dd($session->getMetadataBag()->getCreated() ;
        $user = $this->getUser();
        $form = $this->createForm(VerifyNumberType::class,$user);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            if($form->isSubmitted() && $form->isValid()){
                $number = $form->get('number')->getData();
                $timeValid = $session->getMetadataBag()->getCreated() + 900;
                // dd($timer. '' .$timeValid);
                if($number === $user->getResetNumber() && $timer <= $timeValid){
                    $user->setResetNumber(null);
                    $user->setSession($session->getId());
                    $entityManager->flush();
                    return $this->redirectToRoute('app_main');
                }elseif ($number !== $user->getResetNumber()  || $timer > $timeValid ){
                    $this->addFlash('warning','Déconnexion');
                    return $this->redirectToRoute('app_logout');
                }
            }
        }
        return $this->render('customer/verified-device.html.twig',['form'=>$form->createView()]);
    }
}
