<?php

namespace AppBundle\CommandHandler;


use AppBundle\Entity\Task;
use AppBundle\Command\TaskUpdateCommand;
use AppBundle\Repositories\TaskRepository;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class TaskUpdateCommandHandler
 * @package AppBundle\CommandHandler
 */
class TaskUpdateCommandHandler
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var RecursiveValidator
     */
    private $validator;

    /**
     * @param TaskRepository $taskRepository
     * @param RecursiveValidator $validator
     */
    public function __construct(TaskRepository $taskRepository, RecursiveValidator $validator)
    {
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
    }

    /**
     * @param TaskUpdateCommand $command
     */
    public function handle(TaskUpdateCommand $command)
    {
        $task = $command->getTask();

        $task->update(
            $command->getTitle(),
            $command->getDescription()
        );

        $this->validate($task);

        $this->taskRepository->save($task);
    }

    /**
     * @param Task $task
     */
    private function validate(Task $task)
    {
        $errors = $this->validator->validate($task);
        if(count($errors) > 0)
            throw new Exception('Validation Error');
    }
}