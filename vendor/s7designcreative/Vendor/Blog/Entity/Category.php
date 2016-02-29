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
	 * @OrderBy({"id" = "DESC"})
     */
    protected $posts;

	/** @Column(type="string", nullable=true) */
	protected $color;

	/** @Column(type="string", nullable=true) */
	protected $slug;

	/** @Column(type="simple_array", nullable=true) */
    protected $children;

	/** @Column(type="integer", nullable=true) */
    protected $ordering;

    /**
     * @return mixed
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param mixed $ordering
     */
    public function setOrdering( $ordering )
    {
        $this->ordering = $ordering;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren( $children )
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

	/**
	 * @return mixed
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param mixed $color
	 */
	public function setColor( $color ) {
		$this->color = $color;
	}

    /**
     * Set up class properties
     */
    public function __construct(){
        $this->posts = new ArrayCollection();
    }


    /**
     * Get posts for category
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Post[]
     */
    public function getPosts($offset = 0, $limit = 10)
    {
        return array_slice($this->posts->toArray(), $offset, $limit);
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