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
                $cap = ucfirst($this->name);
                $this->errors[$this->name] = "$cap tem de ser $errorMessage.";
                $this->valid = false;
            }
        }

        return $this;
    }

    public function int()
    {
        return $this->_filter(FILTER_VALIDATE_INT, 'numerico');
    }

    public function float()
    {
        return $this->_filter(FILTER_VALIDATE_FLOAT, 'decimal');
    }

    public function bool()
    {
        return $this->_filter(FILTER_VALIDATE_BOOLEAN, 'booleano');
    }

    public function email()
    {
        return $this->_filter(FILTER_VALIDATE_EMAIL, 'um email');
    }

    public function url()
    {
        return $this->_filter(FILTER_VALIDATE_URL, 'um url');
    }

    public function str() {
        if ($this->valid && $this->value !== null) {
            if (!is_string($this->value)) {
                $cap = ucfirst($this->name);
                $this->errors[$this->name] = "$cap tem de ser uma string.";
                $this->valid = false;
            }
        }

        return $this;
    }

    public function required()
    {
        if ($this->valid) {
            if ($this->value === null) {
                $cap = ucfirst($this->name);
                $this->errors[$this->name] = "$cap é obrigatório.";
                $this->valid = false;
            }
        }

        return $this;

    }

    public function min($length)
    {

        $cap = ucfirst($this->name);
        if ($this->valid && $this->value !== null) {
            if (is_string($this->value)) {

                if (strlen($this->value) < $length) {
                    $this->errors[$this->name] = "$cap tem de ter pelo menos $length caracteres.";
                    $this->valid = false;
                }

            } else {

                if ($this->value < $length) {
                    $this->errors[$this->name] = "$cap tem de ser superior a $length.";
                    $this->valid = false;
                }

            }
        }

        return $this;

    }

    public function max($length)
    {
        $cap = ucfirst($this->name);

        if ($this->valid && $this->value !== null) {
            if (is_string($this->value)) {

                if (strlen($this->value) > $length) {
                    $this->errors[$this->name] = "$cap não pode exceder $length caracteres.";
                    $this->valid = false;
                }

            } else {

                if ($this->value > $length) {
                    $this->errors[$this->name] = "$cap tem de ser inferior a $length.";
                    $this->valid = false;
                }

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