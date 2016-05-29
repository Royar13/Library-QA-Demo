<?php

class CreateUserValidator extends UserValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("username", "name", "type", "password");
    }

    public function validateCreate($user) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateUsernameFree($user->username))
            $this->addError("username", "שם המשתמש תפוס");
        if (!$this->validatePasswordRepeat($user->password, $user->passwordRepeat))
            $this->addError("passwordRepeat", "אימות הסיסמא אינו זהה לסיסמא");
        
        return $this->isValid();
    }

}
