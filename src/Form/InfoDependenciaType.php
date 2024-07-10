<?php

namespace App\Form;

use App\Entity\InfoDependencia;
use App\Entity\InfoReparticion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoDependenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
            "required" => false,
            "label" => "Nombre",
            "help"  => "Ingrese un nombre válido",
            "attr" => [
                "class" => "form-control",
                'placeholder' => 'Ingrese un nombre válido',
                "required" => true
            ],
        ])
            ->add('abreviatura', TextType::class, [
            "required" => false,
            "label" => "Abreviatura",
            "help"  => "Ingrese un nombre válido",
            "attr" => [
                "class" => "form-control",
                'placeholder' => 'Ingrese un nombre válido',
                "required" => true
            ],
        ])
            // ->add('dependenciaRenaperId')
            // ->add('codigoGdeba')
            // ->add('tipoDependenciaId')
            // ->add('tipoDelegacion')
            ->add('descripcion', TextType::class, [
            "required" => false,
            "label" => "Descripcion",
            "help"  => "Ingrese descripcion válida",
            "attr" => [
                "class" => "form-control",
                'placeholder' => 'Ingrese un nombre válido',
                "required" => true
            ],
        ])
            // ->add('clase')
            // ->add('partidoId')
            // ->add('fechaVigenciaDesde')
            // ->add('fechaVigenciaHasta')
            // ->add('fechaCarga')
            // ->add('usuarioId')
            // ->add('estadoDependenciaId')
            // ->add('externa')
//             ->add('reparticion', EntityType::class, [
//                 'class' => InfoReparticion::class,
// 'choice_label' => 'id',
//             ])
//             ->add('dependenciaReferencia', EntityType::class, [
//                 'class' => InfoDependencia::class,
// 'choice_label' => 'id',
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoDependencia::class,
        ]);
    }
}
