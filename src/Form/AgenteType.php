<?php

namespace App\Form;

use App\Entity\Agente;
use App\Entity\Dependencia;
use App\Entity\Genero;
use App\Entity\Reparticion;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AgenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apellido', TextType::class, [
                "required" => false,
                "label" => "Apellido",
                "help"  => "Ingrese un apellido válido",
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un apellido válido',
                    "required" => true
                ],
            ])
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
            ->add('legajo', TextType::class, [
                "required" => false,
                "label" => "Legajo",
                "help"  => "Ingrese un legajo válido",
                "attr" => [
                    "pattern" => "[0-9]{1,10}",
                    "title"     => "Ingrese un numero de hasta 10 dígitos ",
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un legajo válido',
                    "required" => false
                ],
            ])
            ->add('numeroDocumento', NumberType::class, [

                "label" => "DNI",
                "help"  => "Ingrese un DNI válido",
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un DNI válido',
                    "pattern" => "[0-9]{8}",
                    "title"     => "Ingrese un numero de hasta 8 dígitos ",
                    "required" => true
                ],
            ])
            ->add('genero', EntityType::class, [
                'class' => Genero::class,
                'choice_label' => 'nombre',
                "label" => "Género",
                "help"  => "Selecciones un Género",
                "placeholder" => "Seleccione un Género",
                "attr" => [
                    "class" => "form-control select2-genero",
                    "required" => true,
                ],
            ])
            ->add('cuil', TextType::class, [
                "label" => "CUIL",
                "help"  => "Ingrese un cuil válido",
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un cuil válido',
                    "pattern" => "[0-9]{11}",
                    "title"     => "Ingrese un numero de hasta 11 dígitos. El CUIL debe contener el DNI.",
                    "required" => true
                ],
                // 'constraints' => [
                //     new Callback([$this, 'validateField2']),
                // ],
            ])
            ->add('categoria', NumberType::class, [
                "required" => false,
                "label" => "Categoría",
                "help"  => "Ingrese una Categoria válida",
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese una Categoria válida',
                    "pattern" => "[0-9]{1,}",
                    "title"     => "Ingrese un numero",
                    "required" => true
                ],
            ])
            // ->add('externo', ChoiceType::class, [
            //     'choices' => array(
            //         'SI' => "1",
            //         'NO' => "0"
            //     ),
            //     "attr" => [
            //         "class" => "form-control select2-externo",
            //         "required" => true,
            //     ],
            //     "label" => "Es externo",
            //     "help"  => "Seleccione si es externo",
            //     "placeholder" => "Seleccione si es externo",
            //     'required' => false,
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agente::class,
        ]);
    }
}
