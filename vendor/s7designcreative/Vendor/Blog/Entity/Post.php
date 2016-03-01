<?php

namespace S7D\Vendor\Blog\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use S7D\Core\Auth\Entity\User;

/**
 * Class Post
 * @package S7D\Vendor\Post\Entity
 *
 * @Entity(repositoryClass="S7D\Vendor\Blog\Repository\PostRepository") @Table(name="post")
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
     * @ManyToOne(targetEntity="S7D\Vendor\Blog\Entity\PostStatus", inversedBy="post")
     */
    protected $status;

    /**
     * @ManyToOne(targetEntity="S7D\Vendor\Blog\Entity\PostType", inversedBy="post")
     */
    protected $type;

    /** @Column(type="integer") */
    protected $author_id;

    /** @Column(type="integer", options={"default" = 0}) */
    protected $views;

	/**
	 * @Column(type="string")
	 */
	protected $slug;

	/**
	 * @return mixed
	 */
	public function getViews() {
		return $this->views;
	}

	/**
	 * @param mixed $views
	 */
	public function setViews( $views ) {
		$this->views = $views;
	}

    /** @Column(type="array") */
    protected $meta;

	/**
	 * @return mixed
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param mixed $slug
	 */
	public function setSlug( $slug ) {
		$this->slug = $slug;
	}

    /**
     * @ManyToOne(targetEntity="S7D\Core\Auth\Entity\User", inversedBy="post")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @ManyToMany(targetEntity="S7D\Vendor\Blog\Entity\Tag", inversedBy="post")
     * @JoinTable(name="post_has_tag")
     */
    protected $tags;

    /**
     * @ManyToMany(targetEntity="S7D\Vendor\Blog\Entity\Category", inversedBy="posts")
     * @JoinTable(name="post_has_category")
     */
    protected $categories;

    /**
     * @OneToMany(targetEntity="S7D\Vendor\Blog\Entity\Comment", mappedBy="post")
     * @JoinTable(name="post_comment")
     **/
    protected $comments;

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments( $comments )
    {
        $this->comments = $comments;
    }

    /**
     * Set up class properties
     */
    public function __construct(){
        $this->tags       = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * Get post type for post
     *
     * @return PostType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set post type for post
     *
     * @param PostType $type
     *
     * @return void
     */
    public function setType(PostType $type)
    {
        $this->type = $type;
    }

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

    /**
     * Get post status
     *
     * @return PostStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set post status
     *
     * @param PostStatus $status
     *
     * @return void
     */
    public function setStatus(PostStatus $status)
    {
        $this->status = $status;
    }


    public function getTags()
    {
        return $this->tags;
    }


    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get author of post
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author of post
     *
     * @param User $author
     *
     * @return void
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getMeta($key)
    {
        return isset($this->meta[$key]) ? $this->meta[$key] : null;
    }

    public function getAllMeta() {
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
     * Get categories for post
     *
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

	public function getFirstCategory() {
		$categories = $this->categories;
		if(!$categories->toArray()) {
			$category = new Category();
			$category->setName('News');
			$category->setId(rand(1,10));
			return $category;
		}
        $category = $categories[0];
        if($category->getId() === 2 && isset($categories[1])) {
            $category = $categories[1];
        }
		return $category;
	}

    public function hasCategory($categoryId) {
        $categories = $this->categories;
        $categories->toArray();
        foreach($categories as $category) {
            if($category->getId() === $categoryId) {
                return true;
            }
        }
        return false;
    }

    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

	public function setCategories($categories) {
		$this->categories = $categories;
	}

}