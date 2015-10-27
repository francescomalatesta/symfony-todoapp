<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Repositories\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController extends Controller
{
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
        $task = new Task(
            $request->get('title'),
            $request->get('description')
        );

        $task->setUser($this->getUser());

        $errors = $this->get('validator')->validate($task);

        if(count($errors) > 0){
            $this->addFlash('validation_errors', 'Both title and description cannot be blank.');
            return new RedirectResponse($this->generateUrl('task_add'));
        }

        $this->getDoctrine()->getRepository('AppBundle:Task')->save($task);

        $this->addFlash('success_message', 'Task added successfully.');
        return new RedirectResponse($this->generateUrl('task_list'));
    }

    /**
     * @Route("/tasks/{type}", name="task_list")
     * @Method("GET")
     */
    public function indexAction(Request $request, $type = 'all')
    {
        $tasks = $this->getDoctrine()->getRepository('AppBundle:Task')->getByUser($this->getUser(), $type);

        return $this->render('task_list.html.twig', compact('tasks', 'type'));
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
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        $task->setTitle($request->get('title'));
        $task->setDescription($request->get('description'));
        $task->setIsDone($request->get('is_done'));

        $errors = $this->get('validator')->validate($task);

        if(count($errors) > 0){
            $this->addFlash('validation_errors', 'Both title and description cannot be blank.');
            return new RedirectResponse($this->generateUrl('task_edit', ['taskId' => $taskId]));
        }

        $this->getDoctrine()->getRepository('AppBundle:Task')->save($task);

        $this->addFlash('success_message', 'Task updated successfully.');
        return new RedirectResponse($this->generateUrl('task_list'));
    }

    /**
     * @Route("/tasks/remove/{taskId}", name="task_remove")
     * @Method("GET")
     */
    public function removeAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);
        $this->getDoctrine()->getRepository('AppBundle:Task')->remove($task);

        $this->addFlash('success_message', 'Task deleted successfully.');
        return new RedirectResponse($request->headers->get('referer', $this->generateUrl('task_list')));
    }

    /**
     * @Route("/tasks/mark/{taskId}/{status}", name="task_mark")
     * @Method("GET")
     */
    public function markAction(Request $request, $taskId, $status)
    {
        $newStatus = ($status == 'done') ? true : false;

        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        $task->setIsDone($newStatus);

        $this->getDoctrine()->getRepository('AppBundle:Task')->save($task);

        $this->addFlash('success_message', 'Task marked as "'.$status.'" successfully.');
        return new RedirectResponse($request->headers->get('referer', $this->generateUrl('task_list')));
    }
}