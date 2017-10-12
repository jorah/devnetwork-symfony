<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AppBundle\Entity\Skill;

class UserType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('firstname')
                ->add('lastname')
                ->add('job')
                ->add('bio')
                ->add('img', FileType::class, [
                    'required' => false
                ])
                ->remove('status')
                ->add('skills', EntityType::class, array(
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'required' => false,
                ))
                ->add('theme', EntityType::class, [
                    'class' => 'AppBundle:Theme',
                    'choice_label' => 'name',
                ])

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }

}
