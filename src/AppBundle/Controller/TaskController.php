<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function indexAction(Request $request)
    {
        $tasks = $this->getUser()->getTasks();

        return $this->render('task_list.html.twig', compact('tasks'));
    }

    /**
     * @Route("/tasks/add", name="task_add")
     * @Method("GET")
     */
    public function addAction()
    {
        return $this->render('task_add.html.twig');
    }

    /**
     * @Route("/tasks/add", name="task_post_add")
     * @Method("POST")
     */
    public function addPostAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->getUser();

        $task = new Task(
            $request->get('title'),
            $request->get('description')
        );

        $task->setUser($user);

        $em->persist($task);
        $em->flush();

        return new RedirectResponse($this->generateUrl('task_list'));
    }

    /**
     * @Route("/tasks/edit/{taskId}", name="task_edit")
     * @Method("GET")
     */
    public function editAction($taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);
        return $this->render('task_edit.html.twig', compact('task'));
    }

    /**
     * @Route("/tasks/edit/{taskId}", name="task_post_edit")
     * @Method("POST")
     */
    public function editPostAction(Request $request, $taskId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        $task->setTitle($request->get('title'));
        $task->setDescription($request->get('description'));

        $em->flush();

        return new RedirectResponse($this->generateUrl('task_list'));
    }

    /**
     * @Route("/tasks/remove/{taskId}", name="task_remove")
     * @Method("GET")
     */
    public function removeAction($taskId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        $em->remove($task);

        $em->flush();

        return new RedirectResponse($this->generateUrl('task_list'));
    }

    /**
     * @Route("/tasks/mark/{taskId}/{status}", name="task_mark")
     * @Method("GET")
     */
    public function markAction($taskId, $status)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        $newStatus = ($status == 'done') ? true : false;
        $task->setIsDone($newStatus);

        $em->flush();

        return new RedirectResponse($this->generateUrl('task_list'));
    }
}