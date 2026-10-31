<?php

namespace App\FormAjax;

use App\Entity\Mark;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

readonly class AjaxMarkService
{
    private const FILE_MARKS = 'mark';
    public function __construct(
        private EntityManagerInterface $em, private Environment $twig,
        private PhotoService $photoService,private  SluggerInterface $slugger )
    {
    }

    public function handleFormData(FormInterface $form): JsonResponse
    {
        if($form->isValid()){
            return $this->handleValidForm($form);
        }
        else
        {
            return $this->handleInvalidForm($form);
        }
    }

    private function handleValidForm(FormInterface $form): JsonResponse
    {
        $mark = $form->getData();
        /** @var UploadedFile $name */
        $name = $form->get('name')->getData();
        $mark->setAlt(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()));
        $mark->setSlug($this->slugger->slug(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()))->lower());
        if($name->getClientOriginalExtension()==='jpeg' || $name->getClientOriginalExtension() ==='jpg'){
            $fichier = $this->photoService->add($name,uniqid(more_entropy: true). '-' . $mark->getSlug(),self::FILE_MARKS,300,300);
            $mark->setName($fichier);
        }
        try{
            $this->em->persist($mark);
            $this->em->flush();
            return new JsonResponse([
                'code'=>Mark::MARK_ADD_SUCCESSFULLY,
                'html'=>$this->twig->render('_components/_list_marks.html.twig',['mark'=>$mark])
            ]);
        }catch (EntityNotFoundException $exception){
            $this->addFlash('warning','Enregistrement impossible '. $exception );
            return $this->render('mark/add.html.twig',['form'=>$form->createView()]);
        }

    }
    private function handleInvalidForm(FormInterface $form): JsonResponse
    {
        return new JsonResponse([
           'code'=>Mark::MARK_BAD_RESPONSE,
           'errors'=>$this->getErrorMessages($form)
        ]);
    }
    private function getErrorMessages(FormInterface $form):array
    {
        $errors = [];
        foreach ($form->getErrors() as $error){
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $child){
            if(!$child->isValid()){
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }
        return $errors;
    }
}
