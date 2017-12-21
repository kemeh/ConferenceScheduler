<?php

namespace ConferenceSchedulerBundle\Form;

use ConferenceSchedulerBundle\Entity\Hall;
use ConferenceSchedulerBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        add('startTime', DateTimeType::class, array(
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control input-inline datetimepicker',
                'data-provide' => 'datetimepicker',
                'html5' => false,
            ]
        ))->
        add('endTime', DateTimeType::class, array(
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control input-inline datetimepicker',
                'data-provide' => 'datetimepicker',
                'html5' => false,
            ]
        ))->
        add('category', ChoiceType::class, array(
            'placeholder' => 'Choose a Category',
            'choices' => array(
                'Lecture' => 'lecture',
                'Break' => 'break',
                )
            )
        )->
        add('hall', EntityType::class, array(
            'placeholder' => 'Choose a Hall',
            'class' => Hall::class,
            'choices' => $options['venueHalls'],
            'choice_label' => 'name',
        ))->
        add('speaker', EntityType::class, array(
            'placeholder' => 'Choose a Speaker',
            'class' => User::class,
            'choice_label' => 'getFullName'
        ))->
        add('Save', SubmitType::class, array('attr' => ['class' => 'btn btn-success']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ConferenceSchedulerBundle\Entity\Session',
            'venueHalls' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return 'session';
    }
}
