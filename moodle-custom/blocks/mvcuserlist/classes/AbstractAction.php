<?php
namespace block_mvcuserlist;

abstract class AbstractAction
{
    abstract public function execute();

    public static function getInstance()
    {
        return new static();
    }
}