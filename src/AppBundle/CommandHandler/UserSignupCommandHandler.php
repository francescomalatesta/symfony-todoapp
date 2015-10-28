<?php

namespace AppBundle\CommandHandler;


use AppBundle\Command\UserSignupCommand;
use AppBundle\Entity\User;
use AppBundle\Repositories\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class UserSignupCommandHandler
 * @package AppBundle\CommandHandler
 */
class UserSignupCommandHandler
{
    /**
     * @var
     */
    private $userRepository;

    /**
     * @var
     */
    private $user;

    /**
     * @var RecursiveValidator
     */
    private $validator;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;


    /**
     * @param UserRepository $userRepository
     * @param RecursiveValidator $validator
     * @param EncoderFactory $encoder
     */
    public function __construct(UserRepository $userRepository, RecursiveValidator $validator, EncoderFactory $encoderFactory)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param UserSignupCommand $command
     * @return User
     * @throws \Exception
     */
    public function handle(UserSignupCommand $command)
    {
        $this->user = new User();

        $this->user->signUp(
            $command->getFullName(),
            $command->getEmailAddress(),
            $this->encoderFactory->getEncoder($this->user)->encodePassword($command->getPassword(), $this->user->getSalt())
        );

        $this->validate();

        $this->userRepository->save($this->user);

        return $this->user;
    }

    /**
     * @throws \Exception
     */
    private function validate()
    {
        $errors = $this->validator->validate($this->user);
        if(count($errors) > 0)
            throw new \Exception('Validation Error');
    }
}