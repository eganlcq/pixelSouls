<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig("First Name", "Your First Name"))
            ->add('lastName', TextType::class, $this->getConfig("Last Name", "Your Last Name"))
            ->add('pseudo', TextType::class, $this->getConfig("Pseudo", "Your Nickname"))
            ->add('email', EmailType::class, $this->getConfig("Email", "Your email"))
            ->add('hash', PasswordType::class, $this->getConfig("Password", "Pick a strong password please"))
            ->add('confirmPassword', PasswordType::class, $this->getConfig("Password confirmation", "Confirm your password"))
            ->add('image', FileType::class, $this->getConfig("Avatar (optionnal)", "", ["required" => false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
