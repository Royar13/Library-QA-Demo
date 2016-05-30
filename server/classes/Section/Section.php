<?php

class Section implements IDatabaseAccess {

    private $db;
    public $id;
    public $name;
    public $bookcaseAmount;

    public function setDatabase($db) {
        $this->db = $db;
    }

    public function readAll() {
        return $this->db->query("select * from sections");
    }

    private function create(SectionValidator $validator) {
        if (!$validator->validateCreate($this))
            return false;

        try {
            $fields["name"] = $this->name;
            $fields["bookcaseAmount"] = $this->bookcaseAmount;
            $this->db->insert("sections", $fields);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function update(SectionValidator $validator) {
        if (!$validator->validateUpdate($this))
            return false;

        try {
            $fields["name"] = $this->name;
            $fields["bookcaseAmount"] = $this->bookcaseAmount;

            $condition["id"] = $this->id;

            $this->db->update("sections", $fields, $condition);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function createFromArray($sections, SectionValidator $validator) {
        foreach ($sections as $section) {
            if (!$section->create($validator))
                throw new Exception ();
        }
    }

    private function updateFromArray($sections, SectionValidator $validator) {
        foreach ($sections as $section) {
            if (!$section->update($validator))
                throw new Exception ();
        }
    }

    public function createUpdateFromArray($createSections, $updateSections, SectionValidator $validator) {
        $this->db->beginTransaction();
        try {
            $this->createFromArray($createSections, $validator);
            $this->updateFromArray($updateSections, $validator);
            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            $this->db->rollBack();
            return false;
        }
    }

}
