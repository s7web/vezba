<?php
namespace S7D\Vendor\Blog\Entity;

/**
 * @Entity @Table(name="post_comment")
 */
class Comment {

	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

	/** @Column(type="integer") **/
	public $author;

	/** @Column(type="string") */
	public $email;

	/**
	 * @ManyToOne(targetEntity="S7D\Vendor\Blog\Entity\Post")
	 **/
	public $post;

	/** @Column(type="text") **/
	public $content;

	/** @Column(type="integer", options={"default" = 0}) **/
	public $status;

	/** @Column(type="datetime", nullable=true) **/
	public $created;
}
