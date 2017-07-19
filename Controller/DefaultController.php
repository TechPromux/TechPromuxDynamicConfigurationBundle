<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Variable\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('TechPromuxDynamicConfigurationBundle:Default:index.html.twig');
    }
}
