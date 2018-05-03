<?php

namespace DigitalRep\CYOSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

 /**
  * @ORM\Entity
  * @ORM\Table(name="user")
  * @ORM\Entity(repositoryClass="DigitalRep\CYOSBundle\Entity\UserRepository")
  */
class User implements AdvancedUserInterface, \Serializable
{
   /**
    * @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    private $id;
	
   /**
    * @ORM\Column(type="string", length=25, unique=true)
	*/
    private $username;
	
   /**
    * @ORM\Column(type="string", length=255)
	*/
    private $password;
	
   /**
    * @ORM\Column(type="string", length=64)
	*/
    private $email;
	
  /**
    * @ORM\Column(type="string", length=255)
	*/ 
	private $salt;
	
   /**
    * @ORM\Column(name="is_active", type="boolean")
	*/
	private $isActive;
	
   /**
    * @ORM\Column(name="accesses", type="integer")
	*/
	private $accesses;
	
   /**
    * @ORM\Column(name="last_accessed", type="date", nullable=true)
	*/
	private $last_accessed;
	
   /**
    * @ORM\Column(type="string", length=32, nullable=true)
	*/
	private $apiKey;
	
   /**
    * @ORM\ManyToMany(targetEntity="Page")
    */
    private $pages;
	
   /**
    * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
    */
    protected $roles;
	
	public function __construct()
	{
		$this->isActive = true;
		$this->salt = md5(uniqid(null, true));
		$this->apiKey = $this->generateApiKey();
		$this->accesses = 0;
		$this->lastAccessed = NULL;
		$this->pages = new ArrayCollection();
		$this->roles = new ArrayCollection();
	}
	
	private function generateApiKey()
	{
		for($i=0;$i<12;$i++)
		{
			$randnum[$i] = rand(0, 9);
		}
	
		for($i=0;$i<20;$i++)
		{
			$char = rand(97, 122);
			$randletters[$i] = chr($char);
		}
		$merged = array_merge($randnum, $randletters);
		shuffle($merged);
		$key = implode($merged);
		return $key;
	}
	
	public function toArray()
	{
		$pages_array = array();
		$i = 0;
		foreach($this->pages as $page)
		{
			$pages_array[$i]['id'] = $page->getId();
			$pages_array[$i]['title'] = $page->getTitle();
			$i++;
		}
		return array(
			'id'		=> $this->id,
			'username' 	=> $this->username,
			'apiKey'    => $this->apiKey,
			'email'     => $this->email,
			'pages'		=> $pages_array
		);
	}
	
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
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
    * Get accesses
    *
    * @return integer
    */
    public function getAccesses()
    {
        return $this->accesses;
    }
	
   /**
    * Inc accesses by 1
    *
    */
	public function incAccesses()
	{
		$this->accesses++;
	}
	
   /**
    * Set LastAccessed
    *
	* @param \DateTime | null 
    */
	public function setLastAccessed(\DateTime $date = null)
	{
		$this->last_accessed = $date ? clone $date : null;
	}
	
   /**
    * Get LastAccessed
    *
    * @return \DateTime | null
    */
	public function getLastAccessed()
	{
		return $this->last_accessed ? clone $this->last_accessed : null;
	}
	
   /**
    * Set email
    *
    * @param $email
    * @return User
    */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

   /**
    * Get email
    *
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
    }

   /**
    * Set username
    *
    * @param $username
    * @return User
    */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

   /**
    * Get username
    *
    * @return string
    */
    public function getUsername()
    {
        return $this->username;
    }

   /**
    * Set password
    *
    * @param $password
    * @return User
    */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
	
   /**
    * Get pages
    *
    * @return string 
    */
    public function getPages()
    {
        return $this->pages;
    }
	
   /**
    * Add page
    * @param \DigitalRep\CYOSBundle\Entity\Page $pages
    * @return Story
    */
    public function addPage($page)
    {
        $this->pages[] = $page;
        return $this;
    }
	
    /**
     * Remove pages
     *
     * @param \DigitalRep\CYOSBundle\Entity\Page $pages
     */
    public function removePage(\DigitalRep\CYOSBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }
	
    /**
     * Clear Pages (Hopefully)
     *
     * @param \DigitalRep\CYOSBundle\Entity\Page $pages
     */
    public function clearPages()
    {
        $this->pages->clear();
    }
	
   /**
    * @see \Serializable::serialize()
	*/
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
			$this->salt));
	}
	
   /**
    * @see \Serializable::unserialize()
	*/
    public function unserialize($serialized)
	{
		list(
			$this->id,
			$this->username,
			$this->password,
			$this->salt
		) = unserialize($serialized);
	}
	
   /**
    * Get isActive
	* 
	* @return boolean
	*/
	public function getIsActive()
	{
		return $this->isActive;
	}
	
   /**
    * Get password
    *
    * @return string
    */
    public function getPassword()
    {
        return $this->password;
    }
	
   /**
    * Get roles
    *
    * @return array
    */
	public function getRoles()
	{
		return $this->roles->toArray();
	}
	
   /**
    * Add role
    * @param \DigitalRep\CYOSBundle\Entity\Role $roles
    * @return User
    */
    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
    }
	
   /**
    * Get apiKey
    *
    * @return apiKey
    */
	public function getApiKey()
	{
		return $this->apiKey;
	}
	
   /**
    * Get salt
    *
    * @return null
    */
	public function getSalt()
	{
		return null;
	}
	
   /**
    * Set salt
    *
    * @param $salt
    */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
	
   /**
    * Erase Credentials
    *
    */
	public function eraseCredentials() { }
	
   /**
    * Equals
    *
    * @return boolean
    */
	public function equals(AdvancedUserInterface $user)
	{
		return $user->getUsername() == $this->getUsername();
	}
}
