<?php

namespace DigitalRep\CYOSBundle\Controller;

use DigitalRep\CYOSBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
	    $user = $this->getUser();
		$pages = null;
		if($user)
		{
			$pages = $user->getPages();
		}
        return $this->render('DigitalRepCYOSBundle:Default:index.html.twig', array('pages' => $pages));
    }
	
	public function adminAction()
	{
        $users = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:User')->findAll();
		
		if(!$users)
		{
			throw $this->createNotFoundException('No pages found');
		}
		return $this->render('DigitalRepCYOSBundle:Default:admin.html.twig', array('users' => $users));
	}
	
    public function pageAction($id = 0)
    {
	    $user = $this->getUser();
		$pages = $user->getPages();
		$lastpage = $user->getPages()->last();
		
		$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Page');
		
		if($id == 0)
		{
			$page = $lastpage;
			$id = $lastpage->getId();
		}
		else
		{
			if($id > $lastpage->getId())
			{
				$page = $repo->find($id);
				$user->addPage($page);
				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();
			}
			else if($id < $lastpage->getId())
			{
				$em = $this->getDoctrine()->getManager();
				$pages = $user->getPages();
				foreach($pages as $page)
				{
					if($page->getId() > $id)
					{
						$user->removePage($page);
					}
				}
				$em->persist($user);
				$em->flush();
				
				$repo = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Page');
				$page = $repo->find($id);
			}
			else
			{
				$page = $repo->find($id);
			}
		}
		
		$query = $repo->createQueryBuilder('p')
					->where('p.previous = :id')
					->setParameter('id', $id)
					->orderBy('p.id')
					->getQuery();
			
		$page->setNexts($query->getResult());

		$newpage = "<p>" . str_replace("\\r\\n", '</p><p>', $page->getContents()) . "</p>";
		$page->setContents($newpage);
				
		return $this->render('DigitalRepCYOSBundle:Default:page.html.twig', array('page' => $page, 'prevs' => $user->getPages()));
    }
	
    public function tocAction()
    {
        $pages = $this->getDoctrine()->getRepository('DigitalRepCYOSBundle:Page')->findAll();
		if(!$pages)
		{
			throw $this->createNotFoundException('No pages found');
		}
        return $this->render(
			'DigitalRepCYOSBundle:Default:toc.html.twig', 
			array('pages' => $pages));
    }
}