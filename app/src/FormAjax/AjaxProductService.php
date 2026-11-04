<?php

namespace App\FormAjax;

use App\Entity\Image;
use App\Entity\Product;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

readonly class AjaxProductService
{
    private const FILE_PRODUCTS = 'product';

    public function __construct( private Environment $twig,
    private EntityManagerInterface $em, private PhotoService $photoService, private SluggerInterface $slugger
    )
    {}

    public function handleformData(FormInterface $form): JsonResponse
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
     * @throws \Exception
     */
    private function handleValidForm(FormInterface $form): JsonResponse
    {
        $product = $form->getData();
        $product->setName($form->get('name')->getData());
        $product->setSlug($this->slugger->slug($product->getName()));
        $product->setMinimum($form->get('stock')->getData());
        /** @var UploadedFile $images */
        $images = $form->get('images')->getData();
        $count = 1;
        foreach($images as $image){
            if($image->getClientOriginalExtension()==='jpeg' ||$image->getClientOriginalExtension()==='jpg'
            || $image->getClientOriginalExtension()==='webp'){
                $fichier = $this->photoService->add($image,$product->getSlug().'-'.$count,self::FILE_PRODUCTS,300,300);
                $photo = new Image();
                $photo->setName($fichier)->setProduct($product);
                $product->addImage($photo);
                $count++;
            }
        }
        try{
            $this->em->persist($product);
            $this->em->flush();
            return new JsonResponse([
                'code'=>Product::PRODUCT_ADD_SUCCESSFULLY,
                'html'=>$this->twig->render('admin/product/list.html.twig')
            ]);
        }catch (EntityNotFoundException $exception){
            $this->addFlash('warning','Enregistrement impossible : ' .$exception);
            return $this->render('admin/product/add_product.html.twig');
        }
    }

    private function handleInvalidForm(FormInterface $form): JsonResponse
    {
        return new JsonResponse([
            'code'=>Product::PRODUCT_BAD_RESPONSE,
            'errors'=> $this->getErrorMessages($form)
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
        return  $errors;
    }

}
