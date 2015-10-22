<?php
namespace S7D\Core\Auth\Entity;

/**
 * @Entity @Table(name="role")
 */
class Role {

	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

	/** @Column(type="string") **/
	public $name;
}