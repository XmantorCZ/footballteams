<?php


namespace models;


class Matches extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'matches';
    protected $primary = 'id';

    protected $fieldConf = [

        'competition' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'played' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'host' => [
            //'belongs-to-one' => '\footballteams\data\Nations',
            'type' => 'TEXT',
            'nullable' => false
        ],
        'away' => [
            //'belongs-to-one' => '\footballteams\data\Nations',
            'type' => 'TEXT',
            'nullable' => false
        ],
        'ghost' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'gaway' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'goals' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'squad' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'is_squad' => [
            'type' => 'TEXT',
            'nullable' => false,
            'default' => false
        ],
        'is_goals' => [
            'type' => 'TEXT',
            'nullable' => false,
            'default' => false
        ],
        'date' => [
            'type' => 'DATE',
            'nullable' => false
        ],
        'viewers' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
        ],
        'stadium' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],

    ];
}