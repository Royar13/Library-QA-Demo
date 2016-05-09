<?php

class LoginValidator extends FormValidator {

    public function __construct() {
        parent::__construct();
        $param = json_decode(file_get_contents("php://input"), true);
        $fieldNames = array("username", "password");
        $this->getInputData($fieldNames, $param);
    }

    public function validate() {
        if (!$this->validateSyntax()) {
            return false;
        }
        try {
            $result = $this->db->query("select * from users where username='{$this->fields["username"]->value}' and password='{$this->fields["password"]->value}'");
            if (mysqli_num_rows($result) == 1) {
                $userData = mysqli_fetch_assoc($result);
                $this->mapDbData($userData);
                return true;
            } else {
                $this->addError("פרטי התחברות שגויים");
                return false;
            }
        } catch (Exception $e) {
            $this->addError("שגיאת מסד נתונים");
            return false;
        }
    }

}

?>