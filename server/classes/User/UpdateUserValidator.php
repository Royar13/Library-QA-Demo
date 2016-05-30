<?php

class UpdateUserValidator extends UserValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("name", "type");
    }

    public function validate($user, $loggedUserHierarchy) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateIdExist($user->id))
            $this->addGeneralError("לא נמצא המשתמש");
        if (!$this->validateType($user->type, $loggedUserHierarchy))
            $this->addError("type", "סוג משתמש לא תקין");

        return $this->isValid();
    }

}
