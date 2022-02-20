<?php

namespace Core;

class  Input {

    protected $params = [];

    /**
     * Class constructor
     *
     * @param array $params  Parameters from the route
     *
     * @return void
     */
    public function __construct($params = false)
    {
        $this->params = $params ?: $_REQUEST;
    }

    /*
     *
     */

    public function exists($key) {
        return !empty($this->params[$key]);
    }

    public function requires($key) {
        if (!$this->exists($key)) {
            $name = ucfirst($key);

            throw new AppException([
                $key => "'$name' é obrigatório."
            ], 400);

            return false;
        }

        return true;
    }

    /*
     *
     */

    private function _filter($key, $filter, $errorMessage) {
        $this->requires($key);

        $value = filter_var($this->params[$key], $filter);
        if ($value === false) {
            $name = ucfirst($key);

            throw new AppException([
                $key => "'$name' tem de ser $errorMessage."
            ], 400);
        }

        return $value;
    }

    public function int($key) {
        return $this->_filter($key, FILTER_VALIDATE_INT, 'numerico');
    }

    public function float($key) {
        return $this->_filter($key, FILTER_VALIDATE_FLOAT, 'decimal');
    }

    public function str($key) {
        $this->requires($key);

        $value = $this->params[$key];
        if (!is_string($value)) {
            $name = ucfirst($key);

            throw new AppException([
                $key => "'$name' tem de ser uma string."
            ], 400);
        }

        $value = trim(htmlspecialchars($value));
        return $value;
    }

    public function bool($key) {
        return $this->_filter($key, FILTER_VALIDATE_BOOLEAN, 'booleano');
    }

    public function email($key) {
        return $this->_filter($key, FILTER_VALIDATE_EMAIL, 'um email');
    }

    public function url($key) {
        return $this->_filter($key, FILTER_VALIDATE_URL, 'um url');
    }

    public function get($key) {
        $this->requires($key);

        return $this->params[$key];
    }
}