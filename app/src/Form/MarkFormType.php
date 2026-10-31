<?php

namespace App\Form;

use App\Entity\Mark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;

class MarkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',FileType::class,['attr'=>['class'=>'form-control form-control-sm'],
                'mapped'=>false,'required'=>true,'label'=>'Nom',
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
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-outline-primary w-100'],
                'label'=>'Soumettre'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
        ]);
    }
}
