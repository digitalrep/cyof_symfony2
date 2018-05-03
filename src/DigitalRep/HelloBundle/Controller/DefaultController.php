<?php

namespace DigitalRep\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DigitalRepHelloBundle:Default:index.html.twig', array('name' => $name));
    }
}
