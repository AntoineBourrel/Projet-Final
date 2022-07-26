<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('edition')
            ->add('game', ChoiceType::class, [
                'choices' => [
                    'Magic The Gathering' => 'Magic the Gathering',
                    'Pokemon' => 'Pokemon',
                    'Yu-Gi-Oh' => 'Yu-Gi-Oh'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'image',
                'required' => false,
                'mapped' => false
            ])
            ->add('comment')
            //->add('publishedAt')
            //->add('user')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
