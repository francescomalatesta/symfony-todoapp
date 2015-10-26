<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataLoader implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, '123456');

        $user->setName('Francesco Malatesta');
        $user->setEmail('francescomalatesta@live.it');
        $user->setPassword($encoded);

        $manager->persist($user);

        $task = new Task('buy milk', 'remember the milk.');
        $task->setUser($user);

        $manager->persist($task);

        $task = new Task('buy more milk', 'and just remember the milk dude');
        $task->setUser($user);

        $manager->persist($task);

        $manager->flush();
    }
}