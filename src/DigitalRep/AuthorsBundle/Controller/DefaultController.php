<?php

namespace DigitalRep\AuthorsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DigitalRepAuthorsBundle:Default:index.html.twig', array('name' => $name));
    }
}
