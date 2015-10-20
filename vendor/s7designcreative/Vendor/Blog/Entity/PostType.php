<?php

namespace S7D\Vendor\Blog\Entity;

/**
 * Class PostType
 * @package S7D\Vendor\Blog\Entity
 *
 * @Entity @Table(name="type")
 */
class PostType
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;

    /**
     * @OneToMany(targetEntity="S7D\Vendor\Blog\Entity\Post", mappedBy="type")
     */
    protected $post;

    /**
     * Get posts related to this type
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Get post type name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set post type name
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
     * Get id of post type
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post type id
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