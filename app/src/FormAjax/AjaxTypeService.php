<?php

namespace App\FormAjax;

use App\Entity\Type;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

readonly class AjaxTypeService
{
private const FILE_TYPES = 'type';

public function __construct(
  private EntityManagerInterface $em,private Environment $twig,
  private PhotoService $photoService,private SluggerInterface $slugger
){}

    /**
     * @param FormInterface $form
     * @return JsonResponse
     */
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

    /**
     * @param FormInterface $form
     * @return JsonResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function handleValidForm(FormInterface $form): JsonResponse
    {
        $type = $form->getData();
        /** @var UploadedFile $name */
        $name = $form->get('name')->getData();
        $type->setAlt(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()));
        $type->setName(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()));
        $type->setSlug($this->slugger->slug(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()))->lower());
        $type->setChild($form->get('child')->getData());
        if($type->isParent() === true ){
            $type->setChild(null);
        }

        if($name->getClientOriginalExtension()==='jpeg' || $name->getClientOriginalExtension() ==='jpg'){
            $fichier = $this->photoService->add($name,uniqid(more_entropy: true). '-' . $type->getSlug(),self::FILE_TYPES,300,300);
            $type->setUrl($fichier);
        }
        try{
            $this->em->persist($type);
            $this->em->flush();
            return new JsonResponse([
                'code'=>Type::TYPE_ADD_SUCCESSFULLY,
                'html'=>$this->twig->render('_components/_list_types.html.twig',['type'=>$type])
            ]);
        }catch (EntityNotFoundException $exception){
            $this->addFlash('warning','Enregistrement impossible '. $exception );
            return $this->render('admin/type/add.html.twig',['form'=>$form->createView()]);
        }

    }

    /**
     * @param FormInterface $form
     * @return JsonResponse
     */
    private function handleInvalidForm(FormInterface $form): JsonResponse
    {
        return new JsonResponse([
            'code'=>Type::TYPE_BAD_RESPONSE,
            'errors'=>$this->getErrorMessages($form)
        ]);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
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
