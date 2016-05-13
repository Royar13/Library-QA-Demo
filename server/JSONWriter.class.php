<?php
class JSONWriter implements IWriter {
    public function write(array $arr) {
        echo json_encode($arr);
    }
}
?>