<?php

namespace DramIO;

Class Whisky
{
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function drink()
    {
        echo "Drinking {$this->name} \n";
    }
}