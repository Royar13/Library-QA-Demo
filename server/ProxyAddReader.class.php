<?php

class ProxyAddReader extends UserProxy implements ICreate {

    private $reader;

    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    public function create(ErrorLogger $errorLogger, $userId) {
        if (!$this->user->authenticate()) {
            throw new Exception();
        }
        return $this->reader->create($errorLogger, $userId);
    }

}

?>