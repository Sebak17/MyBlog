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
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class UserChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password_old', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj hasło!',
                    ]),
                     new SecurityAssert\UserPassword([
                        'message' => 'Hasło jest niepoprawne!',
                    ]),
                ],
            ])
            ->add('password_new', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj nowe hasło!',
                    ]),
                    new Length([
                        'min'        => 6,
                        'max'        => 32,
                        'minMessage' => 'Minimalna długość hasła to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość hasła to {{ limit }}!',
                    ])
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
