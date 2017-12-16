<?php

namespace ConferenceSchedulerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)->
        add('address', TextType::class)->
        add('category', ChoiceType::class, array(
            'choices' => array(
                'Choose a Category' => 'Choose a category',
                'Hotel' => 'Hotel',
                'Conference Center' => 'Conference Center',
                'Convention Center' => 'Convention Center',
                'Outdoor Venue' => 'Outdoor Venue',
                'Theater' => 'Theater',
                'Stadium' => 'Stadium',
                'Rooftop' => 'Rooftop',
                'University' => 'University'
            )
        ))->
        add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ConferenceSchedulerBundle\Entity\Venue'
        ));
    }

    public function getBlockPrefix()
    {
        return 'venue';
    }
}
