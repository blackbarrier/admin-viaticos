<?php

namespace App\Form;

use App\Entity\Configuracion;
use App\Repository\ConfiguracionRepository;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{

    private $security;


    public function __construct(Security $security, ConfiguracionRepository $rConf)
    {
        $this->security = $security;
        $this->rRol = $rConf;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $security = $this->security;
        $usuario = $security->getUser();

        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre',
                    'data-validation' => '[NOTEMPTY]'
                ]
            ])
            ->add('valor', TextType::class, [
                'label' => 'Valor',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'data-validation' => '[NOTEMPTY]'
                ]
            ])
            ->add('leyenda', TextareaType::class, [
                'label' => 'Leyenda',
                'attr' => [
                    'placeholder' => '',
                    'data-validation' => '[NOTEMPTY]'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Configuracion::class,
        ]);
    }
}
