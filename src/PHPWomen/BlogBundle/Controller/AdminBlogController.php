<?php
/**
 * User: PHPetra
 * Date: 2/12/14
 * Time: 5:42 PM
 * 
 */

namespace PHPWomen\BlogBundle\Controller;

use PHPWomen\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
class AdminBlogController extends Controller {

    /**
     * @Route("/", name="blog-admin-index")
     * @Method({"GET", "POST"})
     *
     * @Template()
     */
    public function indexAction()
    {
        var_dump('admin-index');
        die;
    }

    /**
     * @Route("/create", name="blog-create")
     * @Method({"GET", "POST"})
     * Secure(roles="ROLE_EDITOR")
     * @Template()
     */
    public function createAction()
    {
        /** @var \PHPWomen\UserBundle\Entity\User $user */
        $user = $this->getUser();

        $post = new Post();
        $post->setTitle('The PHPwomen first blog post');
        $post->setIntro('With a one line introduction');
        $post->setAuthor($user);
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