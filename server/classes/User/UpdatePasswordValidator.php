<?php

class UpdatePasswordValidator extends UserValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("password");
    }

    public function validate($user) {
        if (!$this->validateSyntax($user))
            return false;
        if (!$this->validateIdExist($user->id))
            $this->addGeneralError("לא נמצא המשתמש");
        if (!$this->validatePasswordRepeat($user->password, $user->passwordRepeat))
            $this->addError("passwordRepeat", "אימות הסיסמא לא זהה לסיסמא");
        if (!$this->validateCurrentPassword($user->id, $user->currentPassword))
            $this->addError("currentPassword", "סיסמא שגויה");

        return $this->isValid();
    }
}
