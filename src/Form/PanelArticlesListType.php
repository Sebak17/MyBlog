<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PanelArticlesListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min'        => 1,
                        'minMessage'        => "ID jest nie prawidłowe!",
                    ])
                ],
            ])
            ->add('title', TextType::class, [
                'constraints' => [
                    new Length([
                        'min'        => 2,
                        'max'        => 100,
                        'minMessage' => 'Minimalna długość tytułu to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość tytułu to {{ limit }}!',
                    ])
                ],
            ])
            ->add('tag', TextType::class, [
                'constraints' => [
                    new Length([
                        'min'        => 2,
                        'max'        => 20,
                        'minMessage' => 'Minimalna długość tagu to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość tagu to {{ limit }}!',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
