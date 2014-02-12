<?php

namespace PHPWomen\BlogBundle\Controller;

use PHPWomen\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/blog")
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
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Post')
            ->findAll();
        return array(
            'posts'     => $posts
        );
    }

    /**
     * @Route("/page/{id}", name="blog-show-post")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction($id)
    {
        $id = 1;
        $post = $this->getDoctrine()
            ->getRepository('PHPWomen\BlogBundle\Entity\Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No thingy found for id '.$id
            );
        }

        var_dump($post); die;
        
    }

    /**
     * @Route("/latest", name="blog-latest")
     * @Method({"GET"})
     * @Template()
     */
    public function latestAction()
    {


    }

    /**
     * @Route("/create", name="blog-create")
     * @Method({"GET", "POST"})
     * Secure(roles="ROLE_BLOG_AUTHOR")
     * @Template()
     */
    public function createAction()
    {
        $post = new Post();
        $post->setTitle('The PHPwomen first blog post');
        $post->setIntro('With a one line introduction');
        $post->setAuthor();
        $post->setText('Lorem ipsum dolor');


        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('blog-latest'));

        return new Response('Created post with id '. $post->getId());
/*
        return $this->render('PHPWomenAdminBundle:Blog:form.html.twig', array(
            'form' => $form->createView()
        ));*/
    }
}
