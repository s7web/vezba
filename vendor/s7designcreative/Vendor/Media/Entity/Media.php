<?php
namespace S7D\Vendor\Media\Entity;

/**
 * @Entity(repositoryClass="S7D\Vendor\Media\Repository\MediaRepository") @Table(name="media")
 */
class Media {

	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

	/** @Column(type="string") **/
	public $file;

	/** @Column(type="string") **/
	public $fileName;

	/** @Column(type="string") **/
	public $type;

	/** @Column(type="string", nullable=true) **/
	public $parent;
}