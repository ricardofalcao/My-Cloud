<?php

namespace Core;

abstract class Controller
{
  /**
     * Parameters from the matched route
     * @var array
     */
    protected $params = [];

    /**
     * Class constructor
     *
     * @param array $params  Parameters from the route
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

}