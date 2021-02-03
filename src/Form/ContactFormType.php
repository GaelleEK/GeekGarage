<?php

namespace App\Form;

use App\Entity\Center;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']])

            ->add('name', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('tel', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('mail', emailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('message', textAreaType::class, [
                'label' => 'Message',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']])

            ->add('submit', submitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-primary m-3']])

            ->add( 'city', EntityType::class, [
            'class' => Center::class,
            'label' => 'Centre de ',
            'attr' => ['class' => 'form-select form-select-sm mb-2'],
            'label_attr' => ['class' => 'form-label mt-2']
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
