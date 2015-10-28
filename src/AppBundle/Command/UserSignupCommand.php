<?php

namespace AppBundle\Command;


class UserSignupCommand
{
    private $fullName;
    private $emailAddress;
    private $password;

    /**
     * UserSignupCommand constructor.
     * @param $fullName
     * @param $emailAddress
     * @param $password
     */
    public function __construct($fullName, $emailAddress, $password)
    {
        $this->fullName = $fullName;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
}