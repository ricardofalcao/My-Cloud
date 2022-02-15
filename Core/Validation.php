<?php

namespace Core;


class Validation
{

    /*
     *
     */

    private $params = [];

    private $errors = array();

    private $name = '';
    private $valid = true;
    private $value = null;

    /**
     * Class constructor
     *
     * @param array $params Parameters from the route
     *
     * @return void
     */
    public function __construct($params = false)
    {
        $this->params = $params ?: $_REQUEST;
    }

    public function name($name)
    {

        $this->name = $name;

        $this->valid = true;
        $this->value = array_key_exists($name, $this->params) ? $this->params[$name] : null;

        return $this;

    }

    public function assert($condition, $message = []) {
        if (!$condition) {
            if (is_array($message)) {
                $this->errors = array_merge($this->errors, $message);
            } else {
                $this->errors[] = $message;
            }

            $this->valid = false;
        }
    }

    private function _filter($filter, $errorMessage)
    {
        if ($this->valid && $this->value !== null) {
            $value = filter_var($this->value, $filter);

            if ($value === false) {
                $this->errors[$this->name] = "$this->name must be $errorMessage.";
                $this->valid = false;
            }
        }

        return $this;
    }

    public function int()
    {
        return $this->_filter(FILTER_VALIDATE_INT, 'numeric');
    }

    public function float()
    {
        return $this->_filter(FILTER_VALIDATE_FLOAT, 'a float');
    }

    public function bool()
    {
        return $this->_filter(FILTER_VALIDATE_BOOLEAN, 'a boolean');
    }

    public function email()
    {
        return $this->_filter(FILTER_VALIDATE_EMAIL, 'an email');
    }

    public function url()
    {
        return $this->_filter(FILTER_VALIDATE_URL, 'an url');
    }

    public function str() {
        if ($this->valid && $this->value !== null) {
            if (!is_string($this->value)) {
                $this->errors[$this->name] = "$this->name must be a string.";
                $this->valid = false;
            }
        }

        return $this;
    }

    public function required()
    {
        if ($this->valid) {
            if ($this->value === null) {
                $this->errors[$this->name] = "$this->name is required";
                $this->valid = false;
            }
        }

        return $this;

    }

    public function min($length)
    {

        if ($this->valid && $this->value !== null) {
            if (is_string($this->value)) {

                if (strlen($this->value) < $length) {
                    $this->errors[$this->name] = "$this->name must be at least $length characters.";
                    $this->valid = false;
                }

            } else {

                if ($this->value < $length) {
                    $this->errors[$this->name] = "$this->name must be greater than $length.";
                    $this->valid = false;
                }

            }
        }

        return $this;

    }

    public function max($length)
    {

        if ($this->valid && $this->value !== null) {
            if (is_string($this->value)) {

                if (strlen($this->value) > $length) {
                    $this->errors[$this->name] = "$this->name must not exceed $length characters.";
                    $this->valid = false;
                }

            } else {

                if ($this->value > $length) {
                    $this->errors[$this->name] = "$this->name must be lesser than $length.";
                    $this->valid = false;
                }

            }
        }

        return $this;

    }

    public function equal($value)
    {
        if ($this->valid && $this->value !== null) {
            if ($this->value != $value) {
                $this->errors[$this->name] = "$this->name must be $value.";
                $this->valid = false;
            }
        }

        return $this;

    }

    public function get() {
        return $this->value;
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}