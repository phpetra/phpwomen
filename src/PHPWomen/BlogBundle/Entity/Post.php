<?php
/**
 * Post Entity
 */

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PHPWomen\UserBundle\Entity\User", inversedBy="posts")
     **/
    protected $author;

    /**
     * @ORM\Column(length=180)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @ORM\Column(length=140)
     */
    protected $tags;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     **/
    protected $categories;
} 