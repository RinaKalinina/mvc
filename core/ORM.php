<?php

namespace Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class ORM
{
    function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            "driver" => DB_DRIVER,
            "host" => DB_HOST,
            "database" => DB_NAME,
            "username" => DB_USER,
            "password" => DB_PASSWORD,
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => DB_PREFIX,
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    function printLog()
    {
        echo '<pre>';
        $log = Capsule::connection()->getQueryLog();

        foreach ($log as $elem) {
            echo 0.01 * $elem['time'] . ': ' . $elem['query'] . ' bind: '
                . json_encode($elem['bindings']) . '<br>';
        }
        echo '</pre>';
    }
}