<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/inscription', name: 'app_inscription_')]
class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param Security $security
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/nouveau', name: 'nouveau', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,Security $security,
                             ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($request);
            if (count($errors) > 0) {
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(), 'errors' => $errors
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();

                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                $user->setRoles(['ROLE_USER']);
                try {
                    $entityManager->persist($user);
                    $entityManager->flush();
                } catch (EntityNotFoundException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage());
                    return $this->redirectToRoute('app_inscription_nouveau');
                }
                // envoi mail activation compte  utilisateur
                return $security->login($user, UserAuthenticator::class, 'main');
            }
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @param $token
     * @param JwtService $jwtService
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param Security $security
     * @return Response
     */
    #[Route('/control/{token}', name: 'check_user')]
    public function verifyUser($token,JwtService $jwtService,UserRepository $userRepository,
                               EntityManagerInterface $em,Security $security): Response
    {
        // if token valid, expired & !modified
        if($jwtService->isValid($token) && !$jwtService->isExpired($token) && $jwtService->check($token, $this->getParameter('app.jwtsecret'))){
            $payload = $jwtService->getPayload($token);
            //user token
            try{
                $user = $userRepository->find($payload['user_id']);
                $user->setStep('B');
                $em->flush();
              return $security->login($user, UserAuthenticator::class, 'main');
            }catch(EntityNotFoundException $e){
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage());
                return $this->redirectToRoute('app_main');
            }
        }
        return $this->redirectToRoute('app_main');
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param Security $security
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/client', name: 'client', methods: ['GET', 'POST'])]
    public function addCustomer(Request $request, ValidatorInterface $validator, Security $security,
         EntityManagerInterface $em,UserRepository $userRepository): Response
    {
        if($this->getUser() === null) {
            $this->addFlash('warning', 'Vous devez créer un compte client pour commander.');
            return $this->redirectToRoute('app_inscription_nouveau');
        }
        if($this->getUser()->getStep() !== 'B') {
            $this->addFlash('warning', 'Vous devez activer votre compte avant de créer un profil client.');
            return $this->redirectToRoute('app_main');
        }
        if($this->getUser()->getCustomer() !== null) {
            $this->addFlash('warning', 'Vous avez déjà un profil client associé à votre compte.');
            return $this->redirectToRoute('app_main');
        }
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($request);
            if (count($errors) > 0) {
                return $this->render('registration/newCustomer.html.twig', [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRepository->find($this->getUser()->getId());
                $user->setStep('C');
                $customer->setUser($user);
                try {
                    $em->persist($customer);
                    $em->flush();
                } catch (EntityNotFoundException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement du client : ' . $e->getMessage());
                    return $this->redirectToRoute('app_inscription_client');
                }

                $this->addFlash('success', 'Votre compte est associé à un profil client. Vous pouvez maintenant passer vos commandes.');
                return $security->login($this->getUser(), UserAuthenticator::class, 'main');
            }
        }
        return $this->render('registration/newCustomer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
