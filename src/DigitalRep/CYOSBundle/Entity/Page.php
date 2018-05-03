<?php

  namespace DigitalRep\CYOSBundle\Entity;
  use Doctrine\ORM\Mapping as ORM;
  use Doctrine\ORM\Mapping\ManyToMany;
  use Doctrine\ORM\Mapping\JoinColumn;
  use JMS\Serializer\Annotation\ExclusionPolicy;
  use JMS\Serializer\Annotation\Expose;


 /**
  * @ORM\Entity
  * @ORM\Table(name="page")
  * @ORM\Entity(repositoryClass="DigitalRep\CYOSBundle\Entity\PageRepository")
  * @ExclusionPolicy("all")
  */
  class Page
  {
  
   /**
    * @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	* @Expose
	*/
    private $id;
	
   /**
    * @ORM\Column(type="string", length=100)
	* @Expose
	*/
    private $title;
	
   /**
    * @ORM\Column(type="text")
    */
    private $contents;
	
   /**
    * @ORM\ManyToOne(targetEntity="Page")
	* @ORM\JoinColumn(name="previous_id", referencedColumnName="id")
	*/
    private $previous;
	
    private $nexts;
  
    public function __construct()
	{
	  $this->nexts = new ArrayCollection();
	}

   /**
    * Get nexts
    *
    * @return nexts
    */
    public function getNexts()
    {
        return $this->nexts;
    }

   /**
    * Set nexts
    *
    * @param $nexts
    * @return Page
    */
    public function setNexts($nexts)
    {
        $this->nexts = $nexts;
        return $this;
    }
	
   /**
    * Get previous
    *
    * @return page
    */
    public function getPrevious()
    {
        return $this->previous;
    }
	
   /**
    * Set previous
    *
    * @param Page $previous
    * @return Page
    */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
        return $this;
    }
  
   /**
    * Get id
    *
    * @return integer 
    */
    public function getId()
    {
        return $this->id;
    }

   /**
    * Set title
    *
    * @param string $title
    * @return Page
    */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

   /**
    * Get title
    *
    * @return string 
    */
    public function getTitle()
    {
        return $this->title;
    }

   /**
    * Get contents
    *
    * @return string 
    */
    public function getContents()
    {
        return $this->contents;
    }
	
   /**
    * Set contents
    *
    * @param string contents
    * @return Page
    */
    public function setContents($contents)
    {
        $this->contents = $contents;
		return $this;
    }
}
