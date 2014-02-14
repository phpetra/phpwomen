<?php
/**
 * User: PHPetra
 * Date: 2/12/14
 * Time: 8:57 PM
 *
 */

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryChoiceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'entity', array(
            'attr'      => array('class' => 'phpw-input-auto'),
            'label'     => 'Select a category',
            'required'    => true,
            'class'     => 'PHPWomen\BlogBundle\Entity\Category',
            'property'  => 'name',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC')
                        //->where('c.id = 1')
                        ;
                },
            //'expanded'=> false,
            //'multiple'   => true
            'empty_value'   => 'Select a category',
            'empty_data'    => null,

        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PHPWomen\BlogBundle\Entity\Category'
        ));
    }

    public function getName()
    {
        return 'phpwomen_blog_category';
    }

}