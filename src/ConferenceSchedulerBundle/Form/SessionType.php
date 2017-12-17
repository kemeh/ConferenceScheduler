<?php

namespace ConferenceSchedulerBundle\Form;

use ConferenceSchedulerBundle\Entity\Hall;
use ConferenceSchedulerBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('topic', TextType::class)->
        add('startTime', DateTimeType::class)->
        add('endTime', DateTimeType::class)->
        add('hall', EntityType::class, array(
            'placeholder' => 'Choose a Hall',
            'class' => Hall::class,
            'choice_label' => 'name',
        ))->
        add('speaker', EntityType::class, array(
            'placeholder' => 'Choose a Speaker',
            'class' => User::class,
            'choice_label' => 'getFullName'
        ))->
        add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ConferenceSchedulerBundle\Entity\Session'
        ));
    }

    public function getBlockPrefix()
    {
        return 'session';
    }
}
