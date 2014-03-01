<?php
/**
 * Category Entity
 */

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPWomen\BlogBundle\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_categories")
 * @ORM\Entity(repositoryClass="PHPWomen\BlogBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=180)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "60",
     *      minMessage = "The category name must be at least {{ limit }} characters long.",
     *      maxMessage = "The category name can not be longer than {{ limit }} characters."
     * )
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="\PHPWomen\BlogBundle\Entity\Post", mappedBy="category")
     * @Assert\Valid()
     **/
    protected $posts;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
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
     * @return Category
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

    public function __toString()
    {
        return 'phpwomen-blog-category';
    }

}
