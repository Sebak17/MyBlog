<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Type;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj status!',
                    ]),
                    new Choice(['VISIBLE', 'INVISIBLE']),
                ],
            ])

            ->add('author', TextType::class, [
                'constraints' => [
                    new Length([
                        'min'        => 2,
                        'max'        => 100,
                        'minMessage' => 'Minimalna nazwa autora to {{ limit }}!',
                        'maxMessage' => 'Maksymalna nazwa autora to {{ limit }}!',
                    ]),
                    new Type([
                        'type' => ['alnum'],
                        'message' => "Autor może posiadać tylko litery i liczby!"
                    ])
                ],
            ])

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

            ->add('titleImage', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj obraz!',
                    ]),
                    new Length([
                        'min'        => 30,
                        'max'        => 50,
                    ]),
                ],
            ])

            ->add('tag', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj tag!',
                    ]),
                    new Length([
                        'min'        => 5,
                        'max'        => 20,
                        'minMessage' => 'Minimalna długość tagu to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość tagu to {{ limit }}!',
                    ]),
                ],
            ])

            ->add('description_short', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj krótki opis!',
                    ]),
                    new Length([
                        'min'        => 0,
                        'max'        => 200,
                        'maxMessage' => 'Maksymalna długość krótkiego opisu to {{ limit }}!',
                    ]),
                ],
            ])

            ->add('text', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj treść artykułu!',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            
            'csrf_protection' => false,
            'cascade_validation' => true,

            'allow_extra_fields' => true,
        ]);
    }
}
