<?php

namespace DigitalRep\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FuckYouController extends Controller
{
  public function indexAction($name, $adjective)
  {
    return $this->render('DigitalRepHelloBundle:FuckYou:index.html.twig', array('name'=>$name, 'adjective'=>$adjective));
	//php template instead
	//return $this->render('AcmeHelloBundle:FuckYou:index.html.php', array('name'=>$name));
  }
}
