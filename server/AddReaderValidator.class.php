<?php

class AddReaderValidator extends InputValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("id", "name", "maxBooks", "readerType");
        $this->setValidation("id", "israeliID");
        $this->setValidation("city", "hebrew");
        $this->setValidation("street", "street");
    }

}