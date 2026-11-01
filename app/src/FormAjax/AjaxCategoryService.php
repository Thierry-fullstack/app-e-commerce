<?php

namespace App\FormAjax;

use App\Entity\Category;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class AjaxCategoryService
{
private const FILE_CATEGORIES = 'category';

public function __construct (
    private EntityManagerInterface $em, private Environment $twig,
    private PhotoService $photoService, private SluggerInterface $slugger)
{
}

    /**
     * @param FormInterface $form
     * @return JsonResponse
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
public function handleFormData(FormInterface $form): JsonResponse
{
    if($form->isValid()){
        return $this->handleValidForm($form);
    }else{
        return $this->handleInvalidForm($form);
    }
}

    /**
     * @param FormInterface $form
     * @return JsonResponse
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
private function handleValidForm(FormInterface $form): JsonResponse
{
    $category = $form->getData();
    /** @var UploadedFile $name */
    $name = $form->get('name')->getData();
    $category->setAlt(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()));
    $category->setSlug($this->slugger->slug(str_replace('.'.$name->getClientOriginalExtension(),'',$name->getClientOriginalName()))->lower());
    if($name->getClientOriginalExtension()==='jpeg' || $name->getClientOriginalExtension()==='jpg'){
        $fichier = $this->photoService->add($name,uniqid(more_entropy: true).'-'.$category->getSlug(),self::FILE_CATEGORIES,300,300);
        $category->setName($fichier);
    }
    try{
        $this->em->persist($category);
        $this->em->flush();
        return new JsonResponse([
            'code'=>Category::CATEGORY_ADD_SUCCESSFULLY,
            'html'=>$this->twig->render('_components/_list_categories.html.twig',['category'=>$category])
        ]);
    }catch (EntityNotFoundException $exception){
        $this->addFlash('warning','Enregistrement impossible : ' .$exception );
        return $this->render('admin/category/add.html.twig');
    }
}

private function handleInvalidForm(FormInterface $form): JsonResponse
{
    return new JsonResponse([
        'code'=>Category::CATEGORY_BAD_RESPONSE,
        'erroirs'=>$this->getErrorMessages($form)
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
