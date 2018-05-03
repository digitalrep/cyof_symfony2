<?php

namespace DigitalRep\CYOSBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use DigitalRep\CYOSBundle\Entity\User;

class Registration
{
	/**
	 * @Assert\Type(type="DigitalRep\CYOSBundle\Entity\User")
	 * @Assert\Valid()
	 */
	 protected $user;
	 
	 public function setUser(User $user)
	 {
		$this->user = $user;
	 }
	 
	 public function getUser()
	 {
		return $this->user;
	 }
}

?>