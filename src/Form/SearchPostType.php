<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchType', ChoiceType::class, [
                'choices' => [
                    'Title' => 'Title',
                    'User' => 'User'
                ]
            ])
            ->add('typePost', ChoiceType::class, [
                'choices' => [
                    'All' => 'All',
                    'Discussion' => 'Discussion',
                    'Request' => 'Request',
                    'Technical problems' => 'Technical problems'
                ]
            ])
            ->add('search', SearchType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
