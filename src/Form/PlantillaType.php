<?php

namespace App\Form;

use App\Entity\Agente;
use App\Entity\Plantilla;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantillaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identificacion')
        // ->add('ultima_mod')
            ->add('agentes', HiddenType::class, [
            'mapped' => false, // Esto evita que el campo se vincule automáticamente a una propiedad de la entidad
            'attr' => [
                'class' => 'hidden-field', // Puedes agregar clases CSS si lo necesitas
            ],
        ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plantilla::class,
        ]);
    }
}
