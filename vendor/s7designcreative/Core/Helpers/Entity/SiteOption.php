<?php
namespace S7D\Core\Helpers\Entity;

/**
 * @Entity(repositoryClass="S7D\Core\Helpers\Repository\SiteOptionRepository")
 */
class SiteOption {

	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	public $id;

	/**
	 * @Column(type="string")
	 */
	public $option_key;

	/**
	 * @Column(type="text")
	 */
	public $option_value;
}
