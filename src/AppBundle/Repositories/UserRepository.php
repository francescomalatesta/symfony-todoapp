<?php

namespace AppBundle\Repositories;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function save(User $user)
    {
        if($user->getId() == null)
            $this->getEntityManager()->persist($user);

        $this->getEntityManager()->flush();
    }
}