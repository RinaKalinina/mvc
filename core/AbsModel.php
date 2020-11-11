<?php

namespace Core;

use \Illuminate\Database\Eloquent\Model;

abstract class AbsModel extends Model
{
    public function getPasswordHash(string $password)
    {
        return sha1(SALT . $password);
    }
}