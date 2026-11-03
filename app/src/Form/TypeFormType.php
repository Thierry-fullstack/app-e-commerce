<?php

namespace App\Form;


use App\Entity\Type;
use App\Repository\TypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;

class TypeFormType extends AbstractType
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
            ->add('isParent',CheckboxType::class,['attr'=>['class'=>'form-check-input'],
                'label'=>'Type principal'])
            ->add('child',EntityType::class,['attr'=>['class'=>'form-select text-primary'],'label'=>'Associé à :',
                'label_attr'=>['class'=>'form-label text-primary'],
                'class'=>Type::class,
                'multiple'=>false,
                'expanded'=>false,
                'placeholder'=>'Choisir',
                'choice_label'=>'slug',
                'query_builder'=> function(TypeRepository $er){
                return $er->createQueryBuilder('t')
                    ->where('t.isParent = :active')
                    ->setParameter('active',true)
                    ->orderBy('t.isParent','DESC');
                },
                ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-primary w-100 '],
                'label'=>'Soumettre'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Type::class,
        ]);
    }
}
