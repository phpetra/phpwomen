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
            ->add('title', null)
            ->add('intro', 'textarea', array(
                'label'     => 'Give a short 3-line intro for the listing pages.',
                'required'  => false
            ))
            ->add('text', 'textarea', array('attr' => array('class' => 'phpw-textarea-big')))
            ->add('date', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array('class' => 'phpw-input-small')
            ))
            ->add('categoryChoice', new CategoryChoiceType())
            ->add('category', new CategoryType())
            ->add('status', 'choice', array(
                'choices' => Post::$statusOptions,
                'attr' => array('class' => 'phpw-input-small')
            ))
            ->add('commentsAllowed', 'checkbox', array('required'  => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PHPWomen\BlogBundle\Entity\Post',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'phpwomen_blog_post';
    }
} 