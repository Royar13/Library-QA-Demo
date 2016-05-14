<?php

class ProxyReadReaders extends UserProxy implements IRead {

    private $reader;

    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    public function readAll() {
        if (!$this->user->authenticate()) {
            throw new Exception();
        }
        return $this->reader->readAll();
    }

}