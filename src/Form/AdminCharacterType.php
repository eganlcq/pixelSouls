<?php

namespace App\Form;

use App\Entity\Fighter;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdminCharacterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfig("Name", "Name of the character"))
            ->add('strength', IntegerType::class, $this->getConfig("Strength", "Strength of the character"))
            ->add('dexterity', IntegerType::class, $this->getConfig("Dexterity", "Dexterity of the character"))
            ->add('vitality', IntegerType::class, $this->getConfig("Vitality", "Vitality of the character"))
            ->add('level', IntegerType::class, $this->getConfig("Level", "Level of the character"))
            ->add('experience', IntegerType::class, $this->getConfig("Experience", "Experience of the character"))
            ->add('defenseWon', IntegerType::class, $this->getConfig("Defenses won", "Defenses won by the character"))
            ->add('defenseLost', IntegerType::class, $this->getConfig("Defenses lost", "Defenses lost by the character"))
            ->add('attackWon', IntegerType::class, $this->getConfig("Attacks won", "Attacks won by the character"))
            ->add('attackLost', IntegerType::class, $this->getConfig("Attacks lost", "Attacks lost by the character"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fighter::class,
        ]);
    }
}
