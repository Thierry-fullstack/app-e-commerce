<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('description',TextareaType::class)
            ->add('price',IntegerType::class)
            ->add('stock',IntegerType::class)
            ->add('categorie', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('images',FileType::class,[
                'label'=>false,
                'multiple'=>true,
                'mapped'=>false,
                'required'=>true,
                'constraints'=>[
                    new Sequentially([
                        new NotBlank(message: '3 photos'),
                        new Count(
                            min: 3, max: 3, minMessage: '3 photos', maxMessage: '3 photos',
                        ),
                    ]),
                    new All([
                        new File(['maxSize'=> '4096k','maxSizeMessage'=>'4Mo']),
                        new Image(['allowPortrait'=>true,'allowSquare'=>true,'allowLandscape'=>true,
                            'mimeTypes'=>['image/jpeg','image/jpg','image/webp'],'minHeight'=>500,'minWidth'=>800])
                    ]),

                ]
            ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-primary w-100'],
            'label'=>'Soumettre'])
            ->addEventListener(FormEvents::POST_SUBMIT,$this->addDate(...))
        ;
    }

    public function addDate(PostSubmitEvent $event):void
    {
        $data = $event->getData();
        if(!$data instanceof Product)return;
        $data->setCreatedAt(new DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
