<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;

class SiteInfoUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj tytuł!',
                    ]),
                    new Length([
                        'min'        => 5,
                        'max'        => 100,
                        'minMessage' => 'Minimalna długość tytułu to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość tytułu to {{ limit }}!',
                    ])
                ],
            ])

            ->add('subtitle', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj podtytuł!',
                    ]),
                    new Length([
                        'min'        => 5,
                        'max'        => 100,
                        'minMessage' => 'Minimalna długość podtytułu to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość podtytułu to {{ limit }}!',
                    ])
                ],
            ])

            ->add('text', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj treść artykułu!',
                    ]),
                ],
            ])

            ->add('bgImage', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj obraz!',
                    ]),
                    new Length([
                        'min'        => 20,
                        'max'        => 100,
                    ]),
                ],
            ])

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'cascade_validation' => true,

            'allow_extra_fields' => true,
        ]);
    }
}
