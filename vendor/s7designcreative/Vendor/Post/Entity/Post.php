<?php

namespace S7D\Vendor\Post\Entity;

/**
 * Class Post
 * @package S7D\Vendor\Post\Entity
 *
 * @Entity @Table(name="role_has_category")
 */
class Post
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $title;

    /** @Column(type="text") */
    protected $content;

    /** @Column(type="string") */
    protected $summary;

    /** @Column(type="datetime") */
    protected $updated;

    /** @Column(type="datetime") */
    protected $created;

    /**
     * Get id of post
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post id
     *
     * @param integer $id
     *
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get post title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set post title
     *
     * @param string $title
     *
     * @return string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get post content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content for post
     *
     * @param string $content
     *
     * @return string
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get summary of post
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set post summary
     *
     * @param string $summary
     *
     * @return void
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * Get date-time when post is last time updated
     *
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set date-time when post is last time updated
     *
     * @param mixed $updated
     *
     * @return void
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get date-time when post is created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set date-time when post is created
     *
     * @param string $created
     *
     * @return void
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}