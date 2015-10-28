<?php
/**
 * Created by PhpStorm.
 * User: francesco
 * Date: 28/10/15
 * Time: 14.53
 */

namespace AppBundle\CommandHandler;


use AppBundle\Command\TaskAddCommand;
use AppBundle\Entity\Task;
use AppBundle\Repositories\TaskRepository;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class TaskAddCommandHandler
 * @package AppBundle\CommandHandler
 */
class TaskAddCommandHandler
{
    private $taskRepository;
    private $validator;

    /**
     * TaskAddCommandHandler constructor.
     * @param $taskRepository
     * @param $validator
     */
    public function __construct(TaskRepository $taskRepository, RecursiveValidator $validator)
    {
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
    }

    /**
     * @param TaskAddCommand $command
     */
    public function handle(TaskAddCommand $command)
    {
        $task = new Task(
            $command->getTitle(),
            $command->getDescription(),
            $command->getUser()
        );

        $this->validate($task);

        $this->taskRepository->save($task);
    }

    private function validate(Task $task)
    {
        $errors = $this->validator->validate($task);
        if(count($errors) > 0)
            throw new Exception('Validation Error');
    }
}