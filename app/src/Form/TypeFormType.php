<?php

namespace App\Form;

use App\Entity\Mark;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['attr'=>['class'=>'form-control form-control-sm']])
            ->add('image',FileType::class,['attr'=>['class'=>'form-control form-control-sm']])

            ->add('isParent',CheckboxType::class,['attr'=>['class'=>'form-check-input']])
            ->add('mark', EntityType::class, ['attr'=>['class'=>'form-select'],
                'class' => Mark::class,
                'choice_label' => 'name',
            ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-primary btn-sm w-100']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Type::class,
        ]);
    }
}
