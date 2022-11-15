<?php

namespace CaioMarcatti12\Repository\Connector;

use CaioMarcatti12\Core\Factory\InstanceFactory;
use CaioMarcatti12\Core\Launcher\Annotation\Launcher;
use CaioMarcatti12\Core\Launcher\Enum\LauncherPriorityEnum;
use CaioMarcatti12\Core\Launcher\Interfaces\LauncherInterface;
use CaioMarcatti12\Env\Objects\Property;
use CaioMarcatti12\Repository\Interfaces\ConnectorInterface;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Jenssegers\Mongodb\Connection;

#[Launcher(LauncherPriorityEnum::BEFORE_LOAD_APPLICATION)]
class MongoDB implements ConnectorInterface, LauncherInterface
{
    public function handler(): void
    {
        $this->connect();
    }

    public function connect(): void
    {
        $capsule = InstanceFactory::createIfNotExists(Manager::class);

        $capsule->addConnection([
            'driver' => 'mongodb',
            'host' => Property::get('mongodb.host', 'host.docker.internal'),
            'database' => Property::get('mongodb.database', 'database'),
            'username' => Property::get('mongodb.username', 'root'),
            'password' => Property::get('mongodb.password', 'password'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'options' => [
                'authSource' => Property::get('mongodb.authSource', 'admin'),
                'authMechanism' => Property::get('mongodb.authMechanism', 'default'),
            ]
        ], 'mongodb');

        $capsule->setAsGlobal();
        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->bootEloquent();
        $capsule->getDatabaseManager()->extend('mongodb', function($config, $name) {
            $config['name'] = $name;

            return new Connection($config);
        });
    }
}