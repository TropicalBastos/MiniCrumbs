<?php

class Crumb {

    protected $uri;
    protected $name;

    public function __construct($name, $uri)
    {
        $this->uri = $uri;
        $this->name = $name;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getName()
    {
        return $this->name;
    }

} 