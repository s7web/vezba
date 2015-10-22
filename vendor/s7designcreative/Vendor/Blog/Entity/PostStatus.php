<?php

namespace S7D\Vendor\Blog\Entity;

/**
 * Class PostStatus
 * @package S7D\Vendor\Blog\Entity
 *
 * @Entity @Table(name="status")
 */
class PostStatus
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;

    /**
     * @OneToMany(targetEntity="S7D\Vendor\Blog\Entity\Post", mappedBy="status")
     */
    protected $post;

    /**
     * Get id for post status
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id for post status
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get name of post status
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name of post status
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
     * Get post by status
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}