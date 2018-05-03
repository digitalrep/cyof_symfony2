<?php

namespace DigitalRep\APIBundle\Controller;

use DigitalRep\CYOSBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UsersController extends Controller
{
	/**
	 * GET /users
	 *
	 * @return array
	 */
	public function getUserAction()
	{
		$apiUser = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();	
		if($apiUser->getAccesses() > 9)
		{
			$today = new \DateTime();
			$interval = $apiUser->getLastAccessed()->diff($today);
			if($interval->format('%a') == 0)
			{
				return new Response(json_encode(array('message' => 'You can only access this API up to 10 times a day')));
			}
		}
		$apiUser->incAccesses();
		$em->persist($apiUser);
		$em->flush();
		
		return new Response(json_encode($apiUser->toArray()));
	}
	
	/**
	 * POST /users
	 *
	 * @return array
	 */
	public function postUsersAction(Request $request)
	{	
		$em = $this->getDoctrine()->getManager();	
		$data = $this->getRequest()->getContent();
		$user = json_decode($data, true);
		
		$newUser = new User();
		$newUser->setUsername($user["username"]);
		$newUser->setPassword($user["password"]);
		$newUser->setEmail($user["email"]);
		
		$factory = $this->get('security.encoder_factory');
		$salt = time();
		$newUser->setSalt($salt);
	
		$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Role');
		$role = $repo->find(1);
		$newUser->addRole($role);
			
		$encoder = $factory->getEncoder($newUser);
		$encoded_password = $encoder->encodePassword($newUser->getPassword(), $newUser->getSalt());
		$newUser->setPassword($encoded_password);

		$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Page');
		$page = $repo->find(1);
		$newUser->addPage($page);	
		
		try
		{
			$em->persist($newUser);
			$em->flush();
		}
		catch (\Exception $e) 
		{
			switch (get_class($e)) 
			{
				case 'Doctrine\DBAL\DBALException':
                $error = "DBAL Exception<br />";
                break;
				case 'Doctrine\DBA\DBAException':
                $error = "DBA Exception<br />";
                break;
				default:
                throw $e;
                break;
			}
		}
		return new Response(json_encode($newUser->toArray()));
	}
	
	/**
	 * PUT /users
	 *
	 * @return array
	 */
	public function putUserAction(Request $request)
	{
		$apiUser = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		if($apiUser->getAccesses() > 9)
		{
			$today = new \DateTime();
			$interval = $apiUser->getLastAccessed()->diff($today);
			if($interval->format('%a') == 0)
			{
				return new Response(json_encode(array('message' => 'You can only access this API up to 10 times a day')));
			}
		}		
		$apiUser->incAccesses();
		$em->persist($apiUser);
		$em->flush();
		
		$data = $this->getRequest()->getContent();
		
		$userinfo = json_decode($data, true);		
		$pagesin = $userinfo["user"]["pages"];

		$count = count($pagesin);
		$user->clearPages();
		$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Page');
		
		for($i=0;$i<$count;$i++)
		{
			if(!isset($pages[$i]))
			{
				$page = $repo->find($pagesin[$i]["id"]);
				$user->addPage($page);
			}
			elseif($pages[$i] != $pagesin[$i])
			{
				$page = $repo->find($pagesin[$i]["id"]);
				$user->addPage($page);
			}
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();
		
		return new Response(json_encode($user->toArray()));
	}
	
	/**
	 * DELETE /users
	 *
	 */
	public function deleteUserAction()
	{
		$apiUser = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();	
		$em->remove($apiUser);
		$em->flush();
		return new Response("Deleted.");
	}
	
	/**
	 * GET /users
	 */
	public function debugAction()
	{
        $users = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:User')->findAll();
		$users_array = array();
		$i = 0;
		foreach($users as $user)
		{
			$users_array[$i]['id'] = $user->getId();
			$users_array[$i]['name'] = $user->getUsername();
			$i++;
		}
		return new Response(json_encode($users_array));
	}
}

?>