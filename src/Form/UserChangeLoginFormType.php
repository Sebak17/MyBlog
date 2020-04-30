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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserChangeLoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj nowy login!',
                    ]),
                    new Length([
                        'min'        => 5,
                        'max'        => 24,
                        'minMessage' => 'Minimalna długość loginu to {{ limit }}!',
                        'maxMessage' => 'Maksymalna długość loginu to {{ limit }}!',
                    ])
                ],
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj hasło!',
                    ]),
                    new UserPassword([
                        'message' => 'Hasło jest niepoprawne!',
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
