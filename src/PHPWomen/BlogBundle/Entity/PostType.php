<?php

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('id', 'hidden')
            ->add('title', null)
            ->add('intro', 'textarea', array(
                'label'     => 'Give a short 3-line intro for the listing pages.',
                'required'  => false
            ))
            ->add('text', 'textarea', array('attr' => array('class' => 'phpw-textarea-big')))
            ->add('date', 'date', array(
                'widget'    => 'single_text',
                'format'    => 'yyyy-MM-dd',
                'attr'      => array('class' => 'phpw-input-small')
            ))
            //->add('category', new CategoryChoiceType()) // not workin in setting the entity for some reason
            ->add('category', 'entity', array(
                'class'     => 'PHPWomen\BlogBundle\Entity\Category',
                'label'     => 'Select a category',
                'property'  => 'name',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC')
                            ;
                    },
                //'expanded'=> false,
                //'multiple'   => true
                'attr'          => array('class' => 'phpw-input-auto'),
                'empty_value'   => 'Select a category',
                'empty_data'    => null,
                'required'      => true
            ))
            ->add('categoryNew', new CategoryType(), array(
                'required'      => false
            ))
            /*->add('status', 'choice', array(
                'choices'   => Post::$statusOptions,
                'attr'      => array('class' => 'phpw-input-auto'),
                'required'  => true,
            ))*/ // TODO maybe add for admins?
            ->add('commentsAllowed', 'choice', array(
                'required'  => true,
                'attr'      => array('class' => 'phpw-input-small'),
                'choices'   => array(0 => 'no', 1 => 'yes'),
                'data'      => 1
            ))
        ;

        // Add a conditional: if we have a chosen Category we use that (and pass a dummy to cat) otherwise category remains required
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $selectedCategory = $data['category'];
                if ($selectedCategory) {
                    $data['categoryNew'] = 'dummy';
                }
            }
        );

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