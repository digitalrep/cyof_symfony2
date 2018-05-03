<?php

namespace DigitalRep\AuthorsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="authors")
 * @ORM\Entity(repositoryClass="DigitalRep\AuthorsBundle\Entity\AuthorRepository")
 */
class Author
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="hometown", type="string", length=255)
     */
    private $hometown;

    /**
     * @ORM\Column(name="birthdate", type="date")
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity="Book", mappedBy="author")
     */
    protected $books;

    public function __construct()
	{
		$this->books = new ArrayCollection();
	}

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setHometown($hometown)
    {
        $this->hometown = $hometown;

        return $this;
    }

    public function getHometown()
    {
        return $this->hometown;
    }

    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBooks($books)
    {
        $this->books = $books;

        return $this;
    }

    public function getBooks()
    {
        return $this->books;
    }
}
