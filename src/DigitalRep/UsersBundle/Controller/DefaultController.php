<?php

namespace DigitalRep\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DigitalRep\CYOSBundle\Entity\User;
use DigitalRep\CYOSBundle\Form\Type\RegistrationType;
use DigitalRep\CYOSBundle\Form\Model\Registration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller
{
    public function loginAction(Request $request)
    {
		$session = $request->getSession();
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) 
		{
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } 
		elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) 
		{
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } 
		else 
		{
            $error = '';
        }

        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'DigitalRepCYOSBundle:Default:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
	
	public function registerAction()
	{
		$registration = new Registration();
		$form = $this->createForm(
			new RegistrationType(), 
			$registration, 
				array('action' => $this->generateUrl('user_create')));
				
		return $this->render(
			'DigitalRepCYOSBundle:Default:register.html.twig',
			array('form' => $form->createView()));
	}
	
	public function createAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(new RegistrationType(), new Registration());
		$form->handleRequest($request);
		if($form->isValid())
		{
			$registration = $form->getData();
			$user = $registration->getUser();
			
			$factory = $this->get('security.encoder_factory');
			$salt = time();
			$user->setSalt($salt);
			
			$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Role');
			$role = $repo->find(1);
			$user->addRole($role);
			
			$encoder = $factory->getEncoder($user);
			$encoded_password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
			$user->setPassword($encoded_password);

			$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Page');
			$page = $repo->find(1);
			$user->addPage($page);
			
			$em->persist($user);
			$em->flush();

			return $this->render('DigitalRepCYOSBundle:Default:login.html.twig');
		}
	}
}
