<?php

namespace AppBundle\Repositories;


use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function getByUser(User $user, $type = 'all')
    {
        $dql = 'SELECT t FROM AppBundle\Entity\Task t WHERE t.user = :user';

        if($type != 'all'){
            $status = ($type == 'done') ? 'true' : 'false';
            $dql .= ' AND t.isDone = ' . $status;
        }

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters([
            'user' => $user
        ]);

        return $query->getResult();
    }

    public function save(Task $task)
    {
        if($task->getId() == null)
            $this->getEntityManager()->persist($task);

        $this->getEntityManager()->flush();
    }

    public function update($taskId, $data)
    {
        $task = $this->find($taskId);

        if(isset($data['title']))
            $task->setTitle($data['title']);

        if(isset($data['description']))
            $task->setDescription($data['description']);

        if(isset($data['is_done']))
            $task->setIsDone($data['is_done']);

        $this->getEntityManager()->flush();
    }

    public function remove($taskId)
    {
        $task = $this->find($taskId);

        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}