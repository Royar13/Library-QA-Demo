<?php

function readBorrowsByReader() {
    $borrow = Factory::makeBookBorrow();
    $borrow->readerId = (new Param())->get("readerId");
    $result = $borrow->readBorrowsByReader();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function readBorrowsByReaderForDisplay() {
    $borrow = Factory::makeBookBorrow();
    $borrow->readerId = (new Param())->get("readerId");
    $result = $borrow->readBorrowsByReaderForDisplay();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function readAllBorrowsByReader() {
    $borrow = Factory::makeBookBorrow();
    $borrow->readerId = (new Param())->get("readerId");
    $result = $borrow->readAllBorrowsByReader();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function readAllBorrowsByBook() {
    $borrow = Factory::makeBookBorrow();
    $borrow->bookId = (new Param())->get("bookId");
    $result = $borrow->readAllBorrowsByBook();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function borrowReturnBooks() {
    $borrow = Factory::makeBookBorrow();
    $reader = Factory::makeReader();

    $param = new Param();
    $borrow->readerId = $param->get("readerId");
    $borrow->borrowBooksIds = $param->get("borrowBooksIds");
    $borrow->returnBooksIds = $param->get("returnBooksIds");

    $reader->id = $param->get("readerId");
    $validator = Factory::makeValidator("Borrow");
    if ($reader->readOne()) {
        $borrow->borrowReturnBooks($validator, $reader->maxBooks, Factory::getUser()->id);
    } else {
        $validator->addGeneralError("לא נמצא קורא עם ת.ז. זו");
    }

    if ($validator->isValid()) {
        $output["success"] = true;
    } else {
        $output = $validator->getErrors();
    }
    Factory::write($output);
}
