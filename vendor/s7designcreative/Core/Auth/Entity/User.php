<?php

namespace S7D\Core\Auth\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use S7D\Vendor\Blog\Entity\Post;

/**
 * @Entity(repositoryClass="S7D\Core\Auth\Repository\UserRepository") @Table(name="user")
 */
class User {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @Column(type="string") **/
	protected $username;

	/** @Column(type="string") **/
	protected $email;

	/** @Column(type="string") **/
	protected $password;

	/** @Column(type="string") **/
	protected $status;

	/** @Column(type="integer") **/
	protected $user_group;

	/**
	 * @OneToMany(targetEntity="S7D\Vendor\Blog\Entity\Post", mappedBy="author")
	 */
	protected $post;


	public function __construct(){
		$this->post = new ArrayCollection();
	}

	/**
	 * @return mixed
	 */
	public function getUserGroup() {
		return $this->user_group;
	}

	/**
	 * @param mixed $user_group
	 */
	public function setUserGroup( $user_group ) {
		$this->user_group = $user_group;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}

	/** @Column(type="string", nullable=true) **/
	protected $guid;

	/** @Column(type="string", nullable=true) **/
	protected $token;

	/**
	 * @return mixed
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @param mixed $token
	 */
	public function setToken( $token ) {
		$this->token = $token;
	}

	/**
	 * @ManyToMany(targetEntity="S7D\Core\Auth\Entity\Role")
	 * @JoinTable(name="users_roles",
	 *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")}
	 *      )
	 **/
	protected $roles;


	/** @Column(type="array", nullable=true) **/
	protected $meta;

	/**
	 * @return mixed
	 */
	public function getMeta()
	{
		return $this->meta;
	}

	/**
	 * @param mixed $meta
	 */
	public function setMeta($meta)
	{
		$this->meta = $meta;
	}

	/**
	 * @return mixed
	 */
	public function getRoles()
	{
		$plain = is_array($this->roles) ? $this->roles : $this->roles->toArray();
		$result = [];
		foreach($plain as $item) {
			$result[] = $item->name;
		}
		return $result;
	}

	/**
	 * @param mixed $roles
	 */
	public function setRoles($roles)
	{
		$this->roles = $roles;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return mixed
	 */
	public function getGuid()
	{
		return $this->guid;
	}

	/**
	 * @param mixed $guid
	 */
	public function setGuid($guid)
	{
		$this->guid = $guid;
	}

	/**
	 * Get posts for user
	 *
	 * @return Post[]
	 */
	public function getPost()
	{
		return $this->post;
	}

	/**
	 * Set posts for user
	 *
	 * @param Post $post
	 */
	public function setPost(Post $post)
	{
		$this->post[] = $post;
	}

}
