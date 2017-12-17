<?php

namespace ConferenceSchedulerBundle\Form;

use ConferenceSchedulerBundle\Entity\Venue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConferenceType extends AbstractType
{
    //TODO: filter the choices of the Venue to "with halls only"...
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('topic', TextType::class)->
        add('startDate', DateType::class, array(
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
        ))->
        add('endDate', DateType::class, array(
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
        ))->
        add('venue', EntityType::class, array(
            'placeholder' => 'Choose a Venue',
            'class' => Venue::class,
            'choice_label' => 'name',
        ))->
        add('description', TextareaType::class)->
        add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ConferenceSchedulerBundle\Entity\Conference'
        ));
    }

    public function getBlockPrefix()
    {
        return 'conference';
    }
}
