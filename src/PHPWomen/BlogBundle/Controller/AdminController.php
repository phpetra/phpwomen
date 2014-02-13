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
 */
class AdminController extends Controller {

    /**
     * List all blog posts for all users
     *
     * @Route("/", name="blog-admin-index")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction()
    {
        // $this->get('security.context')->isGranted('ROLE_ADMIN')
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
     * @Template()
     */
    public function saveAction(Request $request)
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $post = new Post();


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
     * @Template()
     */
    public function createDummyAction()
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $post = new Post();
        $post->setTitle('Another blog post');
        $post->setIntro('With a one line introduction');
        $post->setAuthor($user);
        $date = new \DateTime();
            $date->format('Y-m-d H:i:s');
        $post->setCreatedOn($date);
        $post->setUpdatedOn($date);
        $post->setText('Lorem ipsum dolor');
        $post->setDate(new \DateTime());
        //$post->setStatus(2);
        $post->setCommentsAllowed(false);


        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('blog-latest'));
    }

} 