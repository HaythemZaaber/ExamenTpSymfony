<?php

namespace App\Form;

use App\Entity\Analyse;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnalyseType extends AbstractType
{

     private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'disabled' => $this->security->isGranted('ROLE_TECH'),
            ])
            ->add('type', TextType::class, [
                'disabled' => $this->security->isGranted('ROLE_TECH'),
            ])
            ->add('date', TextType::class, [
                'disabled' => $this->security->isGranted('ROLE_TECH'),
            ]);
           if (!$this->security->isGranted('ROLE_TECH')) {
            // Enable the selection of patients only if the user is not a technician
            $builder->add('patients', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'id',
                'multiple' => true,
                'disabled'=>true
            ]);
        }
           if ($this->security->isGranted('ROLE_TECH')) {
            $builder->add('validated', CheckboxType::class, [
                'label' => 'validation',
                'required' => false,
            ]);
        }
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Analyse::class,
        ]);
    }
}
