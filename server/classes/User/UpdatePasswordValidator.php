<?php

class UpdatePasswordValidator extends UserValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("password");
    }

}
