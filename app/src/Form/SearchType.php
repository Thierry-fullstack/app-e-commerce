<?php

namespace App\Form;

use App\Entity\Category;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories',EntityType::class,[
                 'class'=>Category::class, 'expanded'=>false, 'multiple'=>true,'mapped'=>false,
                  'attr'=>['class'=>'form-select'],
                ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-warning w-100'],'label'=>'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=>SearchData::class,
            'method'=>'GET',
            'crsf_protection'=>false
        ]);
    }
}
