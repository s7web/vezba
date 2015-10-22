<?php

namespace S7D\Vendor\Blog\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Tag
 * @package S7D\Vendor\Blog\Entity
 *
 * @Entity @Table(name="tag")
 */
class Tag
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;

    /**
     * @ManyToMany(targetEntity="S7D\Vendor\Blog\Entity\Post", mappedBy="tag")
     */
    protected $post;

    /**
     * Set up class properties
     */
    public function __construct(){
        $this->post = new ArrayCollection();
    }

    /**
     * Get posts with specific tag
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set post to specific tag
     *
     * @param Post $post
     *
     * @return void
     */
    public function setPost(Post $post)
    {
        $this->post[] = $post;
    }

    /**
     * Get tag name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name for tag
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get id for tag
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id for tag
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}