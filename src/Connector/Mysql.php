<?php

namespace CaioMarcatti12\Repository\Connector;

use CaioMarcatti12\Core\Factory\InstanceFactory;
use CaioMarcatti12\Core\Launcher\Annotation\Launcher;
use CaioMarcatti12\Core\Launcher\Enum\LauncherPriorityEnum;
use CaioMarcatti12\Core\Launcher\Interfaces\LauncherInterface;
use CaioMarcatti12\Env\Objects\Property;
use CaioMarcatti12\Repository\Interfaces\ConnectorInterface;
use Illuminate\Database\Capsule\Manager;

#[Launcher(LauncherPriorityEnum::BEFORE_LOAD_APPLICATION)]
class Mysql implements ConnectorInterface, LauncherInterface
{
    public function handler(): void
    {
        $this->connect();
    }

    public function connect(): void
    {
        $capsule = InstanceFactory::createIfNotExists(Manager::class);

        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => Property::get('mongodb.database', 'host.docker.internal'),
            'database' => Property::get('mongodb.database', 'database'),
            'username' => Property::get('mongodb.username', 'root'),
            'password' => Property::get('mysql.password', 'password'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ], 'mysql');

//        $capsule->setAsGlobal();
//        $capsule->bootEloquent();
    }
}