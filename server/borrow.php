<?php

function readBorrowsByReader() { //fix to borrow class
    $reader = Factory::makeReader();
    $reader->id = (new Param())->get("readerId");
    $result = $reader->getBorrowedBooks();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function readBorrowsByReaderForDisplay() { //fix to borrow class
    $borrow = Factory::makeBookBorrow();
    $borrow->readerId = (new Param())->get("readerId");
    $result = $borrow->readBorrowsForDisplay();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function readAllBorrowsByReader() { //fix to borrow class
    $borrow = Factory::makeBookBorrow();
    $borrow->readerId = (new Param())->get("readerId");
    $result = $borrow->readAllReaderBorrows();
    $output["borrows"] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $output["borrows"][] = $row;
    }
    Factory::write($output);
}

function borrowReturnBooks() {
    $success = false;
    $reader = Factory::makeReader();
    $param = new Param();
    $reader->id = $param->get("readerId");
    $errorLogger = new ErrorLogger();
    if ($reader->readOne()) {
        $borrowBooksId = $param->get("borrowBooksId");
        $returnBooksId = $param->get("returnBooksId");
        $reader->borrowReturnBooks($borrowBooksId, $returnBooksId, $errorLogger, Factory::getUser()->id);
    } else {
        $errorLogger->addGeneralError("לא נמצא קורא עם ת.ז. זו");
    }

    if ($errorLogger->isValid()) {
        $output["success"] = true;
    } else {
        $output = $errorLogger->getErrors();
    }
    Factory::write($output);
}
