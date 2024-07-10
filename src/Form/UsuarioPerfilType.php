<?php

namespace App\Form;

use App\Entity\Dependencia;
use App\Entity\Genero;
use App\Entity\Reparticion;
use App\Entity\Rol;
use App\Entity\Usuario;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioPerfilType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
            ->add('nombre', TextType::class,[
                "required" => false,
                "label" => false,
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un Nombre',
                ],
            ])
            ->add('apellido',TextType::class,[
                "required" => false,
                "label" => false,                    
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un Apellido',
                ],
            ])
            ->add('cuil',TextType::class,[
                "required" => false,
                "label" => false,                
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un CUIL válido',
                ],
            ])
            ->add('dni', NumberType::class,[
                "required" => false,
                "label" => false,                
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un DNI válido',
                ],
            ])
            ->add('reparticion', EntityType::class, [
                'class' => Reparticion::class,
                'choice_label' => 'nombre',
                "required" => true,
                "label" => false, 
                "placeholder" => "Seleccione una Repartición",
                "attr" => [
                    "class" => "form-control",
                   
                ],
            ])
            ->add('dependencia', EntityType::class, [
                'class' => Dependencia::class,
                'choice_label' => 'nombre',
                "required" => true,
                "label" => false, 
                "placeholder" => "Seleccione una Dependencia",
                "attr" => [
                    "class" => "form-control"
                ],
            ])
            ->add('genero', EntityType::class, [
                'class' => Genero::class,
                'choice_label' => 'nombre',
                "required" => true,   
                "label" => false,  
                "placeholder" => "Seleccione un Género",            
                "attr" => [
                    "class" => "form-control",                    
                ],
            ])
            ->add('rol', EntityType::class, [
                'class' => Rol::class,
                'choice_label' => 'descripcion',
                "required" => true, 
                "label" => false,
                "placeholder" => "Seleccione un Rol",
                "attr" => [
                    "class" => "form-control",                    
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
