<?php

use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSqlDriver;


return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOPgSql\Driver::class,
                'params' => [
                    'host'     => 'localhost',
                    'port'     => 5432,                    
                    'user'     => 'postgres',
                    'password' => '',
                    'dbname'   => 'blog',
                ]
            ],            
        ],        
    ],
];