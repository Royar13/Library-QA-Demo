<?php

abstract class UserProxy {

    protected $user;

    public function setUser(User $user) {
        $this->user = $user;
        $this->user->fetchLoggedUser();
    }

    public function getUserId() {
        return $this->user->id;
    }

}

?>