<?php
interface ICreate {
    public function create(ErrorLogger $errorLogger, $userId);
}