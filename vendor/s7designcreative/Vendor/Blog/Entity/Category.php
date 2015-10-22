<?php

namespace S7D\Vendor\Blog\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Category
 * @package S7D\Vendor\Blog\Entity
 *
 * @Entity @Table(name="category")
 */
class Category
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;

    /**
     * @ManyToMany(targetEntity="S7D\Vendor\Blog\Entity\Post", mappedBy="categories")
     */
    protected $posts;

    /**
     * Set up class properties
     */
    public function __construct(){
        $this->posts = new ArrayCollection();
    }

    /**
     * Get posts for category
     *
     * @return Post[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set posts for category
     *
     * @param Post $posts
     *
     * @return void
     */
    public function setPosts(Post $posts)
    {
        $this->posts[] = $posts;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}