<?php
/**
 * User: PHPetra
 * Date: 2/12/14
 * Time: 8:57 PM
 * 
 */

namespace PHPWomen\BlogBundle\Entity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('intro', null, array(
                'label'     => 'Give a short 3-line intro for the listing pages.',
                'required'  => false
            ))
            ->add('text', 'textarea')
            ->add('date', 'date')
            ->add('status', 'choice', array('choices' => Post::$statusOptions))
            ->add('commentsAllowed', 'checkbox')
            ->add('save', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PHPWomen\BlogBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'phpwomen_blog_post';
    }
} 