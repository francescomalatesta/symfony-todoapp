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
 * Class TaskAddCommand
 * @package AppBundle\Command
 */
class TaskAddCommand
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
     * @var User
     */
    private $user;

    /**
     * @param $title
     * @param $description
     * @param User $user
     */
    public function __construct($title, $description, User $user)
    {
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
}