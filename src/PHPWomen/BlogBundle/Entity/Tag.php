<?php
/**
 * Tag Entity
 */

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPWomen\BlogBundle\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_tags")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=63)
     */
    protected $tag;

    /**
     * @var Post[] $post
     *
     * @ORM\ManyToMany(targetEntity="Post", inversedBy="tags")
     * @ORM\JoinTable(name="blog_posts_tags",
     *          joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")}
     *      )
     */
    private $posts;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return Tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add posts
     *
     * @param \PHPWomen\BlogBundle\Entity\Post $posts
     * @return Tag
     */
    public function addPost(\PHPWomen\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \PHPWomen\BlogBundle\Entity\Post $posts
     */
    public function removePost(\PHPWomen\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
