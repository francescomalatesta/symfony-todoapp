<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Command\TaskAddCommand;
use AppBundle\Command\UserSignupCommand;
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
        $this->container->get('command_bus')->handle(new UserSignupCommand('Francesco Malatesta', 'francescomalatesta@live.it', '123456'));

        $user = $manager->getRepository('AppBundle:User')->findOneBy(['name' => 'Francesco Malatesta']);

        $this->container->get('command_bus')->handle(new TaskAddCommand('buy milk', 'remember the milk.', $user));
        $this->container->get('command_bus')->handle(new TaskAddCommand('buy more milk', 'and just remember the milk mate', $user));
    }
}