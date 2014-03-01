<?php

namespace PHPWomen\BlogBundle\Controller;

use PHPWomen\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * All routes for te blog frontend
 * Visible to the world
 *
 * @Route("/blog")
 *
 */
class DefaultController extends Controller
{

    /**
     * List blog entries, paginated
     *
     * @Route("/", name="blog-index")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $limit = $request->get('limit', null);
        $offset = $request->get('offset', null);

        $posts = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Post')
            ->fetchLatestPosts($limit, $offset);

        return array(
            'posts'     => $posts
        );
    }

    /**
     * Read one post
     *
     * @Route("/page/{slug}", name="blog-show-post")
     * @Method({"GET"})
     * @Template("PHPWomenBlogBundle:Default:post.html.twig")
     * // TODO regex slug
     */
    public function showAction($slug)
    {
        $post = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Post')
            ->findOneBy(array('slug' => $slug, 'status' => Post::STATUS_PUBLISHED));

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for slug '. $slug
            );
        }

        return array(
            'post'     => $post
        );
    }

    /**
     * The latest posts
     *
     * @Route("/latest", name="blog-latest-posts")
     * @Method({"GET"})
     * @Template()
     */
    public function latestAction()
    {


    }

    /**
     * Posts by category
     *
     * @Route("/category/{category}", name="blog-posts-by-category")
     * @Method({"GET"})
     * @Template("PHPWomenBlogBundle:Default:index.html.twig")
     */
    public function postsByCategoryAction($category, Request $request)
    {
        $limit = $request->get('limit', null);
        $offset = $request->get('offset', null);

        $posts = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Post')
            ->fetchPostsByCategoryName($category, $limit, $offset);

        return array(
            'posts'     => $posts,
            'category'  => $category
        );

    }

    /**
     * Posts by user
     *
     * @Route("/user/{username}", name="blog-posts-by-user")
     * @Method({"GET"})
     * @Template()
     */
    public function postsByUserAction()
    {


    }

    /**
     * Partial that lists all the categories that have at least one post
     *
     * @Route("/categories", name="blog-categories")
     * @Method({"GET"})
     * @Template("PHPWomenBlogBundle:Default:list-categories.html.twig")
     */
    public function listCategoriesAction($limit = null)
    {
        $categories = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Category')
            ->fetchCategoriesWithPosts()
        ;
        return array(
            'categories'     => $categories
        );
    }

}
