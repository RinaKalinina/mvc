<?php

namespace Core;

use Core\Interfaces\StorageInterface;

class StorageSession implements StorageInterface
{
    public function __construct()
    {
        session_start();
    }

    public function get()
    {
        return $_SESSION[self::SESSION_INDEX_USER];
    }

    public function set($data)
    {
        $_SESSION[self::SESSION_INDEX_USER] = $data;
    }

}