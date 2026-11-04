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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['attr'=>['class'=>'form-control form-control-sm my-2'],'label'=>'Nom',
            'label_attr'=>['class'=>'text-primary'],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank(),
                        new Length(max: 50),
                        new Regex([
                            'pattern'=>'/^[0-9a-zA-ZÀ-ÿ\s\'-]+$/',
                            'htmlPattern'=>'[0-9a-zA-ZÀ-ÿ\s\'-]+',
                            'message'=>'Uniquement Chiffres,lettres, des espaces, des apostrophes et des tirets'
                        ])
                    ])
                ]
            ])
            ->add('description',TextareaType::class,
                ['attr'=>['class'=>'form-control mb-4 h-25','rows'=>'3'],
                    'label_attr'=>['class'=>'text-primary'],
                    'constraints'=>[
                        new Sequentially([
                            new NotBlank(),
                            new Length(max: 255),
                            new Regex([
                                'pattern'=>'/^[0-9a-zA-ZÀ-ÿ\s\'-]+$/',
                                'htmlPattern'=>'[0-9a-zA-ZÀ-ÿ\s\'-]+',
                                'message'=>'Uniquement Chiffres,lettres, des espaces, des apostrophes et des tirets'
                            ])
                        ])
                    ]])
            ->add('price',NumberType::class,['attr'=>['class'=>'form-control form-control-sm'],
                'label_attr'=>['class'=>'text-primary'],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank(),
                        new Positive(),
                    ])
                ],
            ])
            ->add('stock',IntegerType::class,['attr'=>['class'=>'form-control form-control-sm'],
                'label_attr'=>['class'=>'text-primary'],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank(),
                        new PositiveOrZero()
                    ])
                ]
            ])
            ->add('category', EntityType::class,['attr'=>['class'=>'form-select form-select-sm mb-4'],
                'label_attr'=>['class'=>'text-primary'],
                'class' => Category::class,
                'choice_label' => 'alt',
                'label'=>'Catégorie',
                'placeholder'=>'Choisir ',
                'constraints'=>[
                    new NotBlank()
                ]
            ])
            ->add('images',FileType::class,['attr'=>['class'=>'form-control form-control-sm mb-4'],
                'label'=>'3 Photos',
                'label_attr'=>['class'=>'text-primary'],
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
                        new Image(['allowPortrait'=>true,'allowSquare'=>true,'allowLandscape'=>false,
                            'mimeTypes'=>['image/jpeg','image/jpg','image/webp'],'minHeight'=>300,'minWidth'=>300])
                    ]),

                ]
            ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-light w-100'],
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
