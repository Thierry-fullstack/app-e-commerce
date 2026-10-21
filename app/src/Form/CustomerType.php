<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname',TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ],'label_attr'=>[
                    'class'=>'form-label mt-2','id'=>'lastname'
                ],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank([
                            'message'=>'Le nom est obligatoire'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Length([
                            'max'=>30,
                            'maxMessage'=>'Le nom ne peut pas dépasser {{ limit }} caractères'
                        ]),
                        new Regex([
                            'pattern'=>'/^[a-zA-ZÀ-ÿ\s\'-]+$/',
                            'htmlPattern'=>'[a-zA-ZÀ-ÿ\s\'-]+',
                            'message'=>'Le nom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets'
                    ])
            ])],
            ])
            ->add('firstname',TextType::class,[
                'label'=>'Prénom',
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ],'label_attr'=>[
                    'class'=>'form-label mt-2','id'=>'firstname'
                ],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank([
                            'message'=>'Le prénom est obligatoire'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Length([
                            'max'=>30,
                            'maxMessage'=>'Le prénom ne peut pas dépasser {{ limit }} caractères'
                        ]),
                        new Regex([
                            'pattern'=>'/^[a-zA-ZÀ-ÿ\s\'-]+$/',
                            'htmlPattern'=>'[a-zA-ZÀ-ÿ\s\'-]+',
                            'message'=>'Le prénom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets'
                    ])
            ])],
            ])
            ->add('address',TextType::class,[
                'label'=>'Adresse',
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ],'label_attr'=>[
                    'class'=>'form-label mt-2','id'=>'address'
                ],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank([
                            'message'=>'L\'adresse est obligatoire'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Length([
                            'max'=>255,
                            'maxMessage'=>'L\'adresse ne peut pas dépasser {{ limit }} caractères'
                        ]),
                        new Regex([
                            'pattern'=>'/^[a-zA-Z0-9À-ÿ\s\'-]+$/',
                            'htmlPattern'=>'[a-zA-Z0-9À-ÿ\s\'-]+',
                            'message'=>'L\'adresse ne peut contenir que des lettres, des chiffres, des espaces, des apostrophes et des tirets'
                        ])
                    ])],
            ])
            ->add('zipcode',TextType::class,[
                'label'=>'Code postal',
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ],'label_attr'=>[
                    'class'=>'form-label mt-2 mx-2','id'=>'zipcode'
                ],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank([
                            'message'=>'Le code postal est obligatoire'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Length([
                            'max'=>5,
                            'min'=>5,
                            'maxMessage'=>'Le code postal ne peut pas dépasser {{ limit }} caractères',
                            'minMessage'=>'Le code postal doit contenir {{ limit }} caractères'
                        ]),
                        new Regex([
                            'pattern'=>'/^\d{5}$/',
                            'htmlPattern'=>'\d{5}',
                            'message'=>'Le code postal doit être composé de 5 chiffres'
                        ])
                    ])],
            ])
            ->add('city',TextType::class,[
                'label'=>'Ville',
                'attr'=>[
                    'class'=>'form-control form-control-sm'
                ],'label_attr'=>[
                    'class'=>'form-label mt-2 mx-2','id'=>'city'
                ],
                'constraints'=>[
                    new Sequentially([
                        new NotBlank([
                            'message'=>'La ville est obligatoire'
                        ]),
                        new \Symfony\Component\Validator\Constraints\Length([
                            'max'=>30,
                            'maxMessage'=>'La ville ne peut pas dépasser {{ limit }} caractères'
                        ]),
                        new Regex([
                            'pattern'=>'/^[a-zA-ZÀ-ÿ\s\'-]+$/',
                            'htmlPattern'=>'[a-zA-ZÀ-ÿ\s\'-]+',
                            'message'=>'La ville ne peut contenir que des lettres, des espaces, des apostrophes et des tirets'
                        ])
                    ])],
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Enregistrer',
                'attr'=>[
                    'class'=>'btn btn-outline-primary mt-3 w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
