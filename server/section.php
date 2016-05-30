<?php

function readAllSections() {
    enforcePermission(5);

    $section = Factory::makeSection();

    $result = $section->readAll();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["sections"][] = $row;
    }
    Factory::write($output);
}

function updateSections() {
    enforcePermission(14);
    $createSections = array();
    $updateSections = array();
    $param = new Param();
    $createSectionsParam = $param->get("createSections");
    $updateSectionsParam = $param->get("updateSections");
    foreach ($createSectionsParam as $sectionParam) {
        $section = Factory::makeSection();
        $section->name = $sectionParam["name"];
        $section->bookcaseAmount = $sectionParam["bookcaseAmount"];
        $createSections[] = $section;
    }
    foreach ($updateSectionsParam as $sectionParam) {
        $section = Factory::makeSection();
        $section->id = $sectionParam["id"];
        $section->name = $sectionParam["name"];
        $section->bookcaseAmount = $sectionParam["bookcaseAmount"];
        $updateSections[] = $section;
    }
    $validator = Factory::makeValidator("Section");
    if ($section->createUpdateFromArray($createSections, $updateSections, $validator)) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}
