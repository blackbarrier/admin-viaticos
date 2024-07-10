<?php

namespace App\Form;

use DateTime;
use App\Entity\Rol;
use App\Entity\Genero;
use App\Entity\Usuario;
use App\Entity\UsuarioRol;
use App\Entity\InfoDependencia;
use App\Entity\InfoReparticion;
use App\Repository\RolRepository;
use App\Repository\GeneroRepository;
use App\Repository\UsuarioRolRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\InfoDependenciaRepository;
use App\Repository\InfoReparticionRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UsuarioType extends AbstractType{
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
            ->add('email', EmailType::class,[
                "required" => true,
                "label" => false,                
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese una dirección de e-mail',
                ],
            ])
            ->add('cuil',TextType::class,[
                "required" => true,
                "label" => false,                
                "attr" => [                   
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un CUIL válido',
                ],
            ])
            ->add('dni', NumberType::class,[
                "required" => true,
                "label" => false,                
                "attr" => [
                    "class" => "form-control",
                    'placeholder' => 'Ingrese un DNI válido',
                ],
            ])
            // ->add('reparticion', EntityType::class, [
            //     'class' => InfoReparticion::class,
            //     'choice_label' => 'nombre',
            //     'query_builder' => function (InfoReparticionRepository $er) {
            //         return $er->createQueryBuilder('d')
            //             ->orderBy('d.nombre', 'ASC');
            //     },
            //     "required" => true,
            //     "label" => false, 
            //     "placeholder" => "Seleccione una Repartición",
            //     "attr" => [
            //         "class" => "form-control",
                   
            //     ],
            // ])
            ->add('dependencia', EntityType::class, [
                'class' => InfoDependencia::class,
                'choice_label' => 'nombre',
                'query_builder' => function (InfoDependenciaRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nombre', 'ASC');
                },
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
                'query_builder' => function (GeneroRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nombre', 'ASC');
                },
                "required" => true,   
                "label" => false,  
                "placeholder" => "Seleccione un Género",            
                "attr" => [
                    "class" => "form-control",                    
                ],
            ])

            /** */
            ->add('rolesActivos', EntityType::class, [
                   'label' => 'Rol',
                    'placeholder' => 'Seleccione ...',
                    'multiple' => true,
                    'attr' => [
                        'class' => 'form-control select2',
                        'data-validation' => 'required',
                    ],
                    'class' => Rol::class,
                    /*'query_builder' => function (RolRepository $rRol){
                        return $rRol->createQueryBuilder('d')
                            ->andWhere('d.id IN (:roles)')
                            ->orderBy('d.descripcion');
                    },*/
                    'choice_name' => 'descripcion',
                    'choice_label' => 'descripcion',
            ]);
                
            /*->add('roles', EntityType::class, [
                'class' => Rol::class,
                'choice_label' => 'nombre', // Replace 'name' with the actual property of the role entity to be displayed in the form
                'query_builder' => function (RolRepository $repository) {
                    return $repository->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC'); // Replace 'name' with the actual property of the role entity to be used for ordering
                },
                'required' => true,
                'label' => 'Roles', // Set the label for the roles field
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
*/
            /*esta es la anterior ↓
            ->add('roles', EntityType::class, [
                'class' => Rol::class,
                'choice_label' => 'descripcion',
                'query_builder' => function (RolRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.descripcion', 'ASC');
                },
                "required" => true, 
                "label" => false,
                "mapped" =>false,
                "multiple" => true,
                "attr" => [
                    "class" => "form-control",  
                    "multiple" => true                  
                ],
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
