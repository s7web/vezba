<?php
namespace S7D\Core\Auth\Entity;

/**
 * @Entity(repositoryClass="S7D\Core\Auth\Repository\UserMetaRepository")
 */
class UserMeta {

	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	public $id;

	/**
	 * @ManyToOne(targetEntity="S7D\Core\Auth\Entity\User")
	 **/
	public $user;

	/**
	 * @Column(type="string")
	 */
	public $option_key;

	/**
	 * @Column(type="text")
	 */
	public $option_value;
}
