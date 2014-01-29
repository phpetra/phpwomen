<?php

namespace PHPWomen\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PHPWomenUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
