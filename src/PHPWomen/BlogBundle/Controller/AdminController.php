<?php
/**
 * User: PHPetra
 * Date: 2/12/14
 * Time: 5:42 PM
 * 
 */

namespace PHPWomen\BlogBundle\Controller;

use PHPWomen\BlogBundle\Entity\Post;
use PHPWomen\BlogBundle\Entity\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminPostController
 *
 * Handles creating a post and related stuffs
 *
 * @package PHPWomen\BlogBundle\Controller
 * @Route("/blog/admin")
 *
 * @todo add the JMSSecurityExtraBundle to get this to work?
 * Secure(roles="ROLE_USER")
 */
class AdminController extends Controller {

    /**
     * @Route("/", name="blog-admin-index")
     * @Method({"GET", "POST"})
     *
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
     * @Route("/create", name="blog-create")
     * @Method({"GET"})
     * Secure(roles="ROLE_EDITOR")
     * @Template()
     */
    public function createAction()
    {
        $post = new Post();
        $post->setDate(new \DateTime('today'));

        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('blog-save'),
        ));

        return $this->render(
            'PHPWomenBlogBundle:Admin:form.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/save", name="blog-save")
     * @Method({"POST"})
     * Secure(roles="ROLE_EDITOR")
     * @Template()
     */
    public function saveAction(Request $request)
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $post = new Post();

        // handy feature: $form->get('saveAndAdd')->isClicked();

        $form = $this->createForm(new PostType(), new Post());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $post = $form->getData();

            $post->setAuthor($user);
            // todo distinguish between create or insert, based on id
            $post->setCreatedOn(new \DateTime('now'));
            $post->setUpdatedOn(new \DateTime('now'));

            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('blog-admin-index'));
        }

        return $this->render(
            'PHPWomenBlogBundle:Admin:form.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/createtest", name="blog-createtest")
     * @Method({"GET", "POST"})
     * Secure(roles="ROLE_EDITOR")
     * @Template()
     */
    public function createDummyAction()
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $post = new Post();
        $post->setTitle('The PHPwomen first blog post');
        $post->setIntro('With a one line introduction');
        $post->setAuthor($user);
        $post->setCreatedOn(new \DateTime('now'));
        $post->setUpdatedOn(new \DateTime('now'));
        $post->setText('Lorem ipsum dolor');
        $post->setDate(new \DateTime());
        //$post->setStatus(2);
        $post->setCommentsAllowed(false);


        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        //return $this->redirect($this->generateUrl('blog-latest'));

        return new Response('Created post with id '. $post->getId());

    }
} 