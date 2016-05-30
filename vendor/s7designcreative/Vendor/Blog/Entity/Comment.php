<?php
namespace S7D\Vendor\Blog\Entity;

/**
 * @Entity(repositoryClass="S7D\Vendor\Blog\Repository\CommentRepository") @Table(name="post_comment")
 */
class Comment {

	/** @Id @Column(type="integer") @GeneratedValue **/
	public $id;

	/** @Column(type="integer", options={"default" = 0}, nullable=true) **/
	public $author;

	/** @Column(type="string", nullable=true) */
	public $email;

	/** @Column(type="string") */
	public $name;

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

	/** @Column(type="integer", options={"default" = 0}) **/
	public $likes;

	/** @Column(type="integer", options={"default" = 0}) **/
	public $dislikes;
}
