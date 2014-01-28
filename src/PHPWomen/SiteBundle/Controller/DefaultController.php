<?php

namespace PHPWomen\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PHPWomenSiteBundle:Default:index.html.twig');
    }
}
