<?php

namespace PHPWomen\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PHPWomenAdminBundle:Default:index.html.twig');
    }
}
