<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repositories\TaskRepository")
 */
class Task
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_done", type="boolean")
     */
    private $isDone;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;


    /**
     * @param $title
     * @param $description
     * @param User $user
     * @param bool|false $isDone
     */
    public function __construct($title, $description, User $user, $isDone = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->isDone = $isDone;

        $this->setUser($user);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool|false
     */
    public function isDone()
    {
        return $this->isDone;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     *
     */
    public function markAsDone()
    {
        $this->isDone = true;
    }

    /**
     *
     */
    public function markAsUndone()
    {
        $this->isDone = false;
    }

    /**
     * @param $title
     * @param $description
     */
    public function update($title, $description)
    {
        $this->title = $title;
        $this->description = $description;
    }
}

