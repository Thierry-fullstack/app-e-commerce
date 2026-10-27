<?php

namespace App\Controller\Admin\Product;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\AddProductType;
use App\Repository\ProductRepository;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/produit', name: 'app_admin_product_')]
final class ProductController extends AbstractController
{
    public const string DIR_PRODUCTS = 'product';

    /**
     * @param ProductRepository $productRepository
     * @return Response
     */
    #[Route('/list', name: 'list')]
    public function index(ProductRepository $productRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $articles = $productRepository->getProductsByCategory('3');
        $datas = $productRepository->findBy([],['name'=>'ASC']);
        $produits = $paginator->paginate(
            $datas,
            $request->query->getInt('page',1),
            8
        );
        return $this->render('admin/product/list_produits.html.twig', ['produits'=>$produits]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @param PhotoService $photoService
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    #[Route('/ajout',name:'add',methods:['GET','POST'])]
    public function addProduct(Request $request,EntityManagerInterface $entityManager,SluggerInterface $slugger,
    PhotoService $photoService,ValidatorInterface $validator):Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = new Product();
        $form = $this->createForm(AddProductType::class,$product);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('admin/product/add_product.html.twig',['form'=>$form->createView(),'errors'=>$errors]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $name = $form->get('name')->getData();
                $product->setSlug($slugger->slug($name)->lower());
                $images = $form->get('images')->getData();
                    $count = 1;
                    foreach ($images as $image){
                        if($image->getClientOriginalExtension()=== 'jpeg' || $image->getClientOriginalExtension()=== 'jpg' ||
                            $image->getClientOriginalExtension()=== 'webp'){
                            $fichier = $photoService->add($image,$product->getSlug(). '-'.$count,self::DIR_PRODUCTS,300,300);
                            $photo = new Image();
                            $photo->setName($fichier);
                            $photo->setProduct($product);
                            $product->addImage($photo);
                            $entityManager->persist($photo);
                            $count++;
                        }
                    }
                try {
                    $entityManager->persist($product);
                    $entityManager->flush();
                    $this->addFlash('success','Produit enregistré avec succès');
                    return $this->redirectToRoute('app_admin_product_list');
                }catch (EntityNotFoundException $exception ){
                    $this->addFlash('warning','Connexion à la base de donnée impossible !');
                    return $this->render('admin/product/add_product.html.twig',['form'=>$form->createView(),
                        'exception'=>$exception]);
                }
            }
        }
        return $this->render('admin/product/add_product.html.twig',['form'=>$form->createView()]);
    }
}
