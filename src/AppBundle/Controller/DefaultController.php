<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        if($this->alreadyLoggedInCheck())
            return new RedirectResponse($this->generateUrl('task_list'));

        return new RedirectResponse($this->generateUrl('login'));
    }

    /**
     * @Route("/signup", name="signup")
     * @Method("GET")
     */
    public function signupAction(Request $request)
    {
        if($this->alreadyLoggedInCheck())
            return new RedirectResponse($this->generateUrl('task_list'));

        return $this->render('signup.html.twig');
    }

    /**
     * @Route("/signup", name="signup-process")
     * @Method("POST")
     */
    public function signupPostAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $encoder = $this->container->get('security.password_encoder');

        $user = new User();

        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setPassword($encoder->encodePassword($user, $request->get('password')));

        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->generateUrl('login'));
    }

    /**
     * @Route("/login", name="login")
     * @Method("GET")
     */
    public function loginAction(Request $request)
    {
        if($this->alreadyLoggedInCheck())
            return new RedirectResponse($this->generateUrl('task_list'));

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', compact('error', 'lastUsername'));
    }



    private function alreadyLoggedInCheck()
    {
        return $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * @Route("/login-check", name="login-check")
     * @Method("POST")
     */
    public function loginPostAction(Request $request)
    {

    }

    /**
     * @Route("/logout", name="logout")
     * @Method("GET")
     */
    public function logoutAction()
    {

    }
}