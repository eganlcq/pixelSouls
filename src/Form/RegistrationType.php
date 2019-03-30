<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    private function getConfig($label, $placeholder, $options = []) {

        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig("First Name", "Your First Name"))
            ->add('lastName', TextType::class, $this->getConfig("Last Name", "Your Last Name"))
            ->add('pseudo', TextType::class, $this->getConfig("Pseudo", "Your Nickname"))
            ->add('hash', PasswordType::class, $this->getConfig("Password", "Pick a strong password please"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
