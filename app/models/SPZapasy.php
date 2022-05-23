<?php


namespace models;


class SPZapasy extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'spzapasy';
    protected $primary = 'id';

    protected $fieldConf = [

        'SOUTEZ' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'ODEHRANE' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'OHOST' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'OAWAY' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'HOST' => [
            //'belongs-to-one' => '\footballteams\data\Nations',
            'type' => 'TEXT',
            'nullable' => false
        ],
        'AWAY' => [
            //'belongs-to-one' => '\footballteams\data\Nations',
            'type' => 'TEXT',
            'nullable' => false
        ],
        'GHOST' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'GAWAY' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'GOLY' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'DATUM' => [
            'type' => 'DATE',
            'nullable' => false
        ],
        'DIVACI' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
        ],
        'STADION' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'VIDEOLINK' => [
            'type' => 'TEXT',
            'nullable' => true,
        ],
        'PLAYERSLINK' => [
            'type' => 'TEXT',
            'nullable' => true,
        ]

    ];
}