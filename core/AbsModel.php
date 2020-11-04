<?php

namespace Core;

abstract class AbsModel
{
    public function getPasswordHash(string $password)
    {
        return sha1(SALT . $password);
    }

    public function getDb(): DB
    {
        return DB::getInstance();
    }

}