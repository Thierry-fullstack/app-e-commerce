<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Type;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',FileType::class,['attr'=>['class'=>'form-control form-control-sm'],
                'mapped'=>false,'required'=>true,'label'=>false,
                'label_attr'=>['class'=>'col-sm-2 col-form-label'],
                'constraints'=>([
                    new Sequentially([
                        new NotBlank(),
                        new File(maxSize: '2M', filenameMaxLength: 2048, extensions: ['jpg','jpeg']),
                        new Image(mimeTypes: ['image/jpeg','image/jpg'], minWidth: 100, minHeight: 100,
                            allowSquare: true, allowLandscape: false, allowPortrait: true)
                    ])
                ])
            ])
            ->add('type',EntityType::class,['attr'=>['class'=>'form-check h-25'],
                'class' => Type::class,
                'label_attr'=>['class'=>'form-label text-danger-emphasis',],
                'choice_label' => 'slug',
                'placeholder'=>'Choisir un type',
                'label'=>'Type de catégorie :',
                'expanded'=>false,
                'multiple' => false,
            ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-primary w-100 '],
                'label'=>'Soumettre'])
            ->addEventListener(FormEvents::POST_SUBMIT,$this->addDate(...))
        ;
    }

    public function addDate(PostSubmitEvent $event):void
    {
        $data = $event->getData();
        if(!$data instanceof Category)return;
        $data->setCreatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
