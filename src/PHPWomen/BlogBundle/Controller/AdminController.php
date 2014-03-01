<?php

namespace PHPWomen\BlogBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\Common\Util\Debug;
use PHPWomen\BlogBundle\Entity\Post;
use PHPWomen\BlogBundle\Entity\PostType;
use PHPWomen\BlogBundle\Entity\Tag;
use PHPWomen\BlogBundle\Exception\UserNotAllowedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class AdminPostController
 *
 * Handles creating a post and related stuffs
 *
 * @package PHPWomen\BlogBundle\Controller
 * @Route("/blog/admin")
 *
 */
class AdminController extends Controller {

    /**
     * List all blog posts for all users
     *
     * @Route("/all", name="blog-admin-all")
     * @Method({"GET"})
     * @Template("PHPWomenBlogBundle:Admin:index.html.twig")
     */
    public function indexAllAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new UnauthorizedHttpException('Sorry but you do not have access to that page');
        }

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('PHPWomenBlogBundle:Post')->findAll();

        /*$date = new \DateTime('today 18:16:50');
        $posts = $em->getRepository('PHPWomenBlogBundle:Post')->fetchLatestPosts($date);*/

        return array(
            'header'    => 'All blog posts',
            'posts'     => $posts
        );
    }

    /**
     * List blog posts for current user
     *
     * @Route("/", name="blog-admin-index")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Post')
            ->findBy(array('author' => $this->getUser()));

        return array(
            'header'    => 'Your blog posts',
            'posts'     => $posts
        );
    }

    /**
     * Display form to create new Post
     *
     * @Route("/new", name="blog-new-post")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction()
    {
        $post = new Post();
        $post->setDate(new \DateTime('today'));

        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('blog-create-post'),
        ));

        return $this->render(
            'PHPWomenBlogBundle:Admin:form.html.twig',
            array(
                'header' => 'Write a new blog post',
                'form' => $form->createView())
        );
    }

    /**
     * Display edit form
     *
     * @Route("/edit/{id}", name="blog-edit-post")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('PHPWomenBlogBundle:Post')->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }
        $this->isUserAllowed($post);

        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('blog-update-post', array('id' => $post->getId())),
        ));

        return $this->render(
            'PHPWomenBlogBundle:Admin:form.html.twig',
            array(
                'form' => $form->createView(),
                'header' => 'Edit the blog post'
            )
        );
    }

    /**
     * Creates a new blog post
     *
     * @Route("/create", name="blog-create-post")
     * @Method({"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createAction(Request $request)
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $post = new Post();

        $form = $this->createForm(new PostType(), $post);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $post = $form->getData();

            $post->setAuthor($user);
            $post->setCreatedOn(new \DateTime('now'));
            $post->setUpdatedOn(new \DateTime('now'));

            $em->persist($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'The post was created!'
            );

            return $this->redirect($this->generateUrl('blog-admin-index'));
        }

        return $this->render(
            'PHPWomenBlogBundle:Admin:form.html.twig',
            array(
                'header' => 'Error creating the post',
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/update/{id}", name="blog-update-post", requirements={"id" = "\d+"})
     * @Method({"POST"})
     * @param $id
     * @Template()
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id = null, Request $request)
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('PHPWomenBlogBundle:Post')->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $this->isUserAllowed($post);

        $form = $this->createForm(new PostType(), $post);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $post = $form->getData();
            $post->setUpdatedOn(new \DateTime('now'));

            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );

            // $request->getSession()->getFlashBag()->set('notice', 'Message sent!');
            //return new RedirectResponse($this->generateUrl('_demo'));

            return $this->redirect($this->generateUrl('blog-admin-index'));
        }

        return $this->render(
            'PHPWomenBlogBundle:Admin:form.html.twig',
            array(
                'header' => 'Error updating the post',
                'form' => $form->createView()
            )
        );
    }

    /**
     * If admin, user has rights
     * If not an admin, this checks if the post belongs to the user.
     *
     * @param $post
     * @return bool
     * @throws \PHPWomen\BlogBundle\Exception\UserNotAllowedException
     */
    protected function isUserAllowed($post)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($this->getUser() == $post->getAuthor()) {
            return true;
        }

        throw new UserNotAllowedException('Sorry the user is not the author');
    }

    /**
     * Deletes a post
     *
     * @Route("/delete/{id}", name="blog-delete-post", requirements={"id" = "\d+"})
     * @Method({"GET","POST"})
     * @Template()
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('PHPWomenBlogBundle:Post')->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Unable to find the post.');
        }

        $this->isUserAllowed($post);

        $em->remove($post);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'The post is deleted!'
        );

        return $this->redirect($this->generateUrl('blog-admin-index'));
    }

    /**
     * Publish / hide a post
     *
     * @Route("/publish/{id}/{status}", name="blog-publish-post", requirements={"id" = "\d+", "status" = "\d+"})
     * @Method({"GET","POST"})
     * @Template()
     * @param $id integer
     * @param $status integer
     * @throws \Doctrine\Common\Proxy\Exception\InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function publishAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('PHPWomenBlogBundle:Post')->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Unable to find the post.');
        }

        $this->isUserAllowed($post);
        if (!array_key_exists($status, Post::$statusOptions)) {
            throw new InvalidArgumentException("Incorrect status received");
        }
        $post->setStatus($status);
        $em->flush();

        // todo set flash message
        return $this->redirect($this->generateUrl('blog-admin-index'));
    }

}