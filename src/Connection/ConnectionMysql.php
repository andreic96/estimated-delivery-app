<?php

namespace Connection;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;

class ConnectionMysql implements ConnectionInterface
{

    /**
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    function getConnection(): EntityManager
    {
        $paths = ['/Entity/'];
        //TODO get these from env
        $dbParams = [
            'driver'   => 'pdo_mysql',
            'host'     => '127.0.0.1',
            'user'     => 'root',
            'password' => 'pass123',
            'dbname'   => 'shipping',
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths);
        $connection = DriverManager::getConnection($dbParams, $config);

        return new EntityManager($connection, $config);
    }

}
