<?php

namespace AppBundle\Controller;

use AppBundle\Command\UserSignupCommand;
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
        try {
            $this->get('command_bus')->handle(
                new UserSignupCommand($request->get('name'), $request->get('email'), $request->get('password'))
            );

            $this->addFlash('success_message', 'User successfully created. You can now login with your chosen credentials.');
            return new RedirectResponse($this->generateUrl('login'));

        } catch (\Exception $e){
            $this->addFlash('error_message', 'Error: one or more fields in the following form were not filled.');
            return new RedirectResponse($this->generateUrl('signup'));
        }
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