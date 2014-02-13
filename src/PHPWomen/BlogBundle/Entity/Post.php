<?php
/**
 * Post Entity
 */

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPWomen\BlogBundle\Entity\Category;
use PHPWomen\BlogBundle\Entity\Tag;
use PHPWomen\BlogBundle\Model\Utils;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="PHPWomen\BlogBundle\Entity\PostRepository")
 */
class Post
{

    const STATUS_NEW        = 0;
    const STATUS_PUBLISHED  = 1;
    const STATUS_HIDDEN     = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(length=180)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "180",
     *      minMessage = "The title must be at least {{ limit }} characters long.",
     *      maxMessage = "The title can not be longer than {{ limit }} characters."
     * )
     */
    private $title;

    /**
     * @ORM\Column(length=200)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $intro;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="PHPWomen\UserBundle\Entity\User", inversedBy="posts")
     * @Assert\Valid()
     **/
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="comments_allowed", type="integer", length=2, nullable=true)
     * @Assert\Choice(choices = {0, 1}, message = "Select whether comments are allowed.")
     */
    private $commentsAllowed;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", length=4)
     * @Assert\Range(min=0, max=11)
     */
    private $status = self::STATUS_NEW;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     * @Assert\DateTime()
     */
    private $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_on", type="datetime")
     * @Assert\DateTime()
     */
    private $updatedOn;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="PHPWomen\BlogBundle\Entity\Category", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $category;

    private $categoryChoice = null;

    /**
     * @var Tag[] $tags
     *
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="posts")
     */
    private $tags;

    /**
     * @var array Options for the status field
     */
    public static $statusOptions = array(
        self::STATUS_NEW        => 'new',
        self::STATUS_PUBLISHED  => 'published',
        self::STATUS_HIDDEN     => 'hidden'
    );

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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->slug = Utils::slugify($title);

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set author
     *
     * @param \PHPWomen\UserBundle\Entity\User $author
     * @return Post
     */
    public function setAuthor(\PHPWomen\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \PHPWomen\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Post
     */
    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return Post
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime 
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     * @return Post
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set category
     * If a category was selected from the choice select, that one has precedence
     *
     * @param \PHPWomen\BlogBundle\Entity\Category $category
     * @return Post
     */
    public function setCategory(\PHPWomen\BlogBundle\Entity\Category $category = null)
    {
        if ($this->categoryChoice) {
            $this->category = $this->categoryChoice;
        } else {
            $this->category = $category;
        }

        return $this;
    }

    /**
     * Get category
     *
     * @return \PHPWomen\BlogBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set intro
     *
     * @param string $intro
     * @return Post
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string 
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tags
     *
     * @param \PHPWomen\BlogBundle\Entity\Tag $tags
     * @return Post
     */
    public function addTag(\PHPWomen\BlogBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \PHPWomen\BlogBundle\Entity\Tag $tags
     */
    public function removeTag(\PHPWomen\BlogBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param \DateTime $commentsAllowed
     * @return Post
     */
    public function setCommentsAllowed($commentsAllowed)
    {
        $this->commentsAllowed = $commentsAllowed;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCommentsAllowed()
    {
        return $this->commentsAllowed;
    }

    /**
     * @param int $status
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null $categoryChoice
     * @return Post
     */
    public function setCategoryChoice($categoryChoice)
    {
        $this->categoryChoice = $categoryChoice;

        return $this;
    }

    /**
     * @return null
     */
    public function getCategoryChoice()
    {
        return $this->categoryChoice;
    }


}
