<?php

namespace DigitalRep\AuthorsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use DigitalRep\AuthorsBundle\Entity\Author;
use DigitalRep\AuthorsBundle\Form\AuthorType;

/**
 * Author controller.
 *
 */
class AuthorController extends Controller
{

    /**
     * Lists all Author entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('DigitalRepAuthorsBundle:Author')->findAll();
		foreach($entities as $ent)
		{
			$books = $ent->getBooks();
			foreach($books as $book)
			{
				$booklist[] = array(
					"id" => $book->getId(),
					"title" => $book->getTitle(),
					"genre" => $book->getGenre(),
					"published" => $book->getPublished());
			}
			$authors[] = array (
			"id" => $ent->getId(),
			"name" => $ent->getName(),
			"birthdate" => $ent->getBirthdate(),
			"hometown" => $ent->getHometown(),
			"books" => $booklist);
		}
        return new Response(json_encode(array('authors' => $authors)));
    }
	
    /**
     * Creates a new Author entity.
     *
     */
    public function createAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$data = $this->getRequest()->getContent();
		$author = json_decode($data, true);
		$newAuthor = new Author();
		$newAuthor->setName($author["author"]["name"]);
		$newAuthor->setBirthdate(new \DateTime($author["author"]["birthdate"]));
		$newAuthor->setHometown($author["author"]["hometown"]);
		$em->persist($newAuthor);
		$em->flush();		
		$author_array = array(
			"id" => $newAuthor->getId(),
			"name" => $newAuthor->getName(),
			"birthdate" => $newAuthor->getBirthdate(),
			"hometown" => $newAuthor->getHometown(),			
		);
		return new Response(json_encode($author_array));	
    }

    /**
     * Finds and displays a Author entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DigitalRepAuthorsBundle:Author')->find($id);
        if (!$entity) { throw $this->createNotFoundException('Unable to find Author entity.'); }
		$author = array (
			"id" => $entity->getId(),
			"name" => $entity->getName(),
			"birthdate" => $entity->getBirthdate(),
			"hometown" => $entity->getHometown()
		);
		return new Response(json_encode($author));
    }

    /**
     * Displays a form to edit an existing Author entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigitalRepAuthorsBundle:Author')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Author entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigitalRepAuthorsBundle:Author:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Author entity.
	 * @ParamConverter("author", class="DigitalRepAuthorsBundle:Author")
     */
    public function deleteAction(Author $author)
    {
		$em = $this->getDoctrine()->getManager();	
		$em->remove($author);
		$em->flush();
		return new Response("Deleted.");
    }

}
