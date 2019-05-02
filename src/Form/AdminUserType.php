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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig("First Name", "First name of the user"))
            ->add('lastName', TextType::class, $this->getConfig("Last Name", "Last name of the user"))
            ->add('pseudo', TextType::class, $this->getConfig("Pseudo", "Nickname of the user"))
            ->add('score', IntegerType::class, $this->getConfig("Score", "Score of the user"))
            ->add('image', FileType::class, $this->getConfig("Avatar", "Picture of the user"))
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
