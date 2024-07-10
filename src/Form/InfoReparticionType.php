<?php

namespace App\Form;

use App\Entity\InfoDependencia;
use App\Entity\InfoReparticion;
use App\Entity\Reparticion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoReparticionType extends AbstractType
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
            ->add('cuit', NumberType::class, [
            "label" => "CUIT",
            "help"  => "Ingrese un CUIT válido",
            "attr" => [
                "class" => "form-control",
                'placeholder' => 'Ingrese un CBU válido',
                "pattern" => "[0-9]{10}",
                "title"     => "Ingrese un numero de CUIL de hasta 10 dígitos ",
                "required" => true
            ],
        ])
            // ->add('rotulo')
        //     ->add('sucursal', TextType::class, [
        //     "required" => false,
        //     "label" => "Sucursal",
        //     "help"  => "Ingrese un nombre válido",
        //     "attr" => [
        //         "class" => "form-control",
        //         'placeholder' => 'Ingrese un nombre válido',
        //         "required" => true
        //     ],
        // ])
            ->add('numeroCuenta', NumberType::class, [

            "label" => "Numero de cuenta",
            "help"  => "Ingrese numero de cuenta válido",
            "attr" => [
                "class" => "form-control",
                'placeholder' => 'Ingrese numero de cuenta válido',
                "pattern" => "[0-9]",
                "title"     => "Ingrese un numero de hasta 11 dígitos ",
                "required" => true
            ],
        ])
            // ->add('digito')
            ->add( 'cbu', NumberType::class, [

            "label" => "CBU",
            "help"  => "Ingrese un CBU válido",
            "attr" => [
                "class" => "form-control",
                'placeholder' => 'Ingrese un CBU válido',
                "pattern" => "[0-9]{11}",
                "title"     => "Ingrese un numero de hasta 11 dígitos ",
                "required" => true
            ],
        ])
//             ->add('estadoReparticionId')
//             ->add('externa')
//             ->add('reparticionReferencia', EntityType::class, [
//                 'class' => Reparticion::class,
// 'choice_label' => 'id',
//             ])
//             ->add('dependencias', EntityType::class, [
//                 'class' => InfoDependencia::class,
// 'choice_label' => 'id',
// 'multiple' => true,
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoReparticion::class,
        ]);
    }
}
