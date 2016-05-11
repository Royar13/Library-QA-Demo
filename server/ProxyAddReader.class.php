<?php

class ProxyAddReader extends UserProxy {

    private $reader;

    public function __construct(Reader $reader) {
        parent::__construct();
        $this->reader = $reader;
    }

    public function create(ErrorLogger $errorLogger) {
        if (!$this->user->authenticate()) {
            throw new Exception();
        }
        return $this->reader->create($errorLogger, $this->user->id);
    }

}

?>