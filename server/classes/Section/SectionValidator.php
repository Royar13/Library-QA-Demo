<?php

class SectionValidator extends InputValidator implements IDatabaseAccess {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("name", "bookcaseAmount");
        $this->setValidation("name", "hebrew");
        $this->setValidation("bookcaseAmount", "positiveInt");
    }

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function validateCreate($section) {
        return $this->validateSyntax($section);
    }

    public function validateUpdate($section) {
        if (!$this->validateSyntax($section))
            return false;

        if (!$this->validateIDExist($section->id))
            $this->addGeneralError("לא נמצא המדור");

        return $this->isValid();
    }

    public function validateIDExist($id) {
        $query = "select id from sections where id=:id";
        $bind[":id"] = $id;
        $result = $this->db->preparedQuery($query, $bind);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return (count($rows) == 1);
    }

}
