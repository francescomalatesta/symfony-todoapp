<?php
/**
 * Created by PhpStorm.
 * User: francesco
 * Date: 28/10/15
 * Time: 14.52
 */

namespace AppBundle\Command;


use AppBundle\Entity\Task;
use AppBundle\Entity\User;

/**
 * Class TaskUpdateCommand
 * @package AppBundle\Command
 */
class TaskUpdateCommand
{
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $description;

    /**
     * @var Task
     */
    private $task;

    /**
     * @param $title
     * @param $description
     * @param Task $task
     */
    public function __construct($title, $description, Task $task)
    {
        $this->title = $title;
        $this->description = $description;
        $this->task = $task;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }
}