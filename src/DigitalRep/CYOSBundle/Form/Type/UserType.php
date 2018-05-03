<?php

namespace DigitalRep\CYOSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('username', 'text');
		$builder->add('password', 'repeated', array(
			'first_name' => 'password',
			'second_name' => 'confirm',
			'type' => 'password'
		));
		$builder->add('email', 'email');
	}

	public function getName()
	{	
		return 'user';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array('data_class' => 'DigitalRep\CYOSBundle\Entity\User'));
	}
}