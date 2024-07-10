<?php

namespace App\Form;

use DateTime;
use App\Entity\Feriado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FeriadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
        ->add('descripcion', TextareaType::class,[
            "attr" =>["style" => "width:300px"]
        ])
        ->add('mes', ChoiceType::class, [
            'choices' =>array(
                "Enero" => 1,
                "Febrero" => 2,
                "Marzo" => 3,
                "Abril" => 4,
                "Mayo" => 5,
                "Junio" => 6,
                "Julio" => 7,
                "Agosto" => 8,
                "Septiembre" => 9,
                "Octubre" => 10,
                "Noviembre" => 11,
                "Diciembre" => 12
            ),
            "attr" =>["class" => "form-control"]
            
        ])
        ->add('dia',  ChoiceType::class, [
            'choices' =>array(
                "01" => 1,
                "02" => 2,
                "03" => 3,
                "04" => 4,
                "05" => 5,
                "06" => 6,
                "07" => 7,
                "08" => 8,
                "09" => 9,
                "10" => 10,
                "11" => 11,
                "12" => 12,
                "13" => 13,
                "14" => 14,
                "15" => 15,
                "16" => 16,
                "17" => 17,
                "18" => 18,
                "19" => 19,
                "20" => 20,
                "21" => 21,
                "22" => 22,
                "23" => 23,
                "24" => 24,
                "25" => 25,
                "26" => 26,
                "27" => 27,
                "28" => 28,
                "29" => 29,
                "30" => 30,
                "31" => 31
            ),
            "attr" =>["class" => "form-control"]
        ])
        ->add('anio', NumberType::class, [
            'constraints' => [
                new Range([
                    'min' => (int) 2024,
                    'max' => date('Y'),
                ])
            ],
            "attr" =>["class" => "form-control"]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feriado::class,
        ]);
    }
}
