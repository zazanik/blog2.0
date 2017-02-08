<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label'     => false,
                'attr'      => array(
                    'placeholder'   => 'Title'
                )
            ))
            ->add('content', TextType::class, array(
                'label'     => false,
                'attr'      => array(
                    'placeholder'   => 'Content'
                )
            ))
            ->add('image', FileType::class, array(
                'label'     => false
            ))
            ->add('category', EntityType::class, array(
                'class'     => Category::class,
                'choice_label'  => 'name'
            ))
            ->add('user', EntityType::class, array(
                'class'     => 'AppBundle:User',
                'choice_label'  => 'username'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post',
            'attr'       => array(
                'novalidate'=>'novalidate'
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
