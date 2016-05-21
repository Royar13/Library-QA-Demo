<?php

class CreateBookValidator extends InputValidator {

    public function __construct() {
        parent::__construct();
        $this->mandatories = array("name", "sectionId", "bookcaseId", "author");
        $this->setValidation("name", "hebrewTitle");
        $this->setValidation("sectionId", "positiveInt");
        $this->setValidation("bookcaseId", "positiveInt");
        $this->setValidation("author", "hebrew");
        $this->setValidation("publisher", "hebrew");
        $this->setValidation("releaseYear", "nonNegativeInt");
        $this->setValidation("copies", "nonNegativeInt");
    }

}
