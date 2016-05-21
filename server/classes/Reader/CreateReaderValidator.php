<?php

class CreateReaderValidator extends InputValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("id", "name", "maxBooks", "readerType");
        $this->setValidation("id", "israeliID");
        $this->setValidation("name", "hebrew");
        $this->setValidation("city", "hebrew");
        $this->setValidation("street", "street");
    }

}