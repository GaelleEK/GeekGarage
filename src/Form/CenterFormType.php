<?php

namespace App\Form;

use App\Entity\Center;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CenterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('address', textType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: 19 rue de Praley'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('city', textType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control',
                    'placeholder' => 'Ex: Vesoul'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('lat', numberType::class, [
                'label' => 'Latitude',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('lon', numberType::class, [
                'label' => 'Longitude',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('submit', submitType::class, [
                'label' => 'Créer',
                'attr' => ['class' => 'btn btn-primary m-3']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Center::class,
        ]);
    }
}
