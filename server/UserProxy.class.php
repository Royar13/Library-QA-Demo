<?php

abstract class UserProxy {

    protected $user;

    public function __construct() {
        $this->user = Factory::makeUser();
        $this->user->fetchLoggedUser();
    }

}

?>