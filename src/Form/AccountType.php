<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig("First Name", "Your First Name"))
            ->add('lastName', TextType::class, $this->getConfig("Last Name", "Your Last Name"))
            ->add('pseudo', TextType::class, $this->getConfig("Pseudo", "Your Nickname"))
            ->add('image', FileType::class, $this->getConfig("Avatar", "Pick the picture you want", ["required" => false]))
        ;

        $builder->get('image')->addModelTransformer(new CallbackTransformer(
            function($image) {
                return null;
            },
            function($image) {
                return $image;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
