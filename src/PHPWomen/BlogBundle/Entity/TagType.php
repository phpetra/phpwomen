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

class TagType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'required'  => false,
            'attr'      => array('class' => 'phpw-input-small')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PHPWomen\BlogBundle\Entity\Tag'
        ));
    }

    public function getName()
    {
        return 'phpwomen_blog_tag';
    }

}