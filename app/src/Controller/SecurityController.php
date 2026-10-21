<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\IntraController;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_main');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        //$lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [ 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param IntraController $intraController
     * @param JwtService $jwtService
     * @param MessageBusInterface $messageBus
     * @param ValidatorInterface $validator
     * @return Response
     * @throws ExceptionInterface
     */
    #[Route(path: '/forgot-password', name: 'app_forgot_password',methods: ['GET','POST'])]
    public function forgotPassword(Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager,
    IntraController $intraController,JwtService $jwtService,MessageBusInterface $messageBus,ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($form);
            if (count($errors) > 0) {
                $this->addFlash('warning', 'formulaire invalide.');
                return $this->render('security/forgot_password.html.twig', [
                    'form' => $form->createView(),'errors' => $errors
                ]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);
                if(!$user){
                    $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
                    return $this->redirectToRoute('app_main');
                }
                $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
                $payload = ['user_id' => $user->getId()];
                $token = $jwtService->generate($headers,$payload,$this->getParameter('app.jwtsecret'));
                $user->setResetToken($token);
                try {
                    $entityManager->flush();
                    $intraController->passwordValidate($user,$token,$messageBus,IntraController::RESET_PASSWORD,IntraController::SUJET,IntraController::PASSWORD);
                    $this->addFlash('success', 'Un email de réinitialisation a été envoyé.');
                    return $this->redirectToRoute('app_main');
                }catch (EntityNotFoundException $e){
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage());
                    return $this->redirectToRoute('app_forgot_password');
                }
            }
        }
        return $this->render('security/forgot_password.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @param string $token
     * @param JwtService $jwtService
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    #[Route('/change-password/{token}',name: 'app_reset_password',methods: ['GET','POST'])]
    public function resetPassword(string $token,JwtService $jwtService,Request $request,ValidatorInterface $validator,
    UserRepository $userRepository,EntityManagerInterface $entityManager,
                                  UserPasswordHasherInterface $userPasswordHasher):Response
    {
        if($jwtService->isValid($token) && !$jwtService->isExpired($token) && $jwtService->check($token,$this->getParameter('app.jwtsecret')))
        {
            $payload = $jwtService->getPayload($token);
            $user = $userRepository->find($payload['user_id']);
            if(!$user){
                $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('app_main');
            }
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            if($request->isMethod('POST')){
                $errors = $validator->validate($request);
                if(count($errors)>0){
                    return $this->render('security/reset_password.html.twig',['form'=>$form->createView(),'errors'=>$errors]);
                }
                if($form->isSubmitted() && $form->isValid()){
                    $user->setResetToken(null);
                    $user->setPassword($userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData()));
                    try{
                        $entityManager->flush();
                        $this->addFlash('success','Mot de passe créé avec succès.');
                        return $this->redirectToRoute('app_login');
                    }catch (EntityNotFoundException $e){
                        $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage());
                        return $this->redirectToRoute('app_forgot_password');
                    }
                }
            }
            return $this->render('security/reset_password.html.twig',['form'=>$form->createView()]);
        }
        $this->addFlash('danger','erreur');
        return $this->redirectToRoute('app_login');
    }
}
