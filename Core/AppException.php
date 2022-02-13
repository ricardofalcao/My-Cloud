<?php


namespace Core;


class AppException extends \Error
{

    private $errors = null;

    public function __construct($errors = [], $code = 0, Throwable $previous = null){
        $this->errors = $errors;

        parent::__construct(implode(", ", $errors), $code, $previous);
    }

    public function getErrors() {
        return $this->errors;
    }

}