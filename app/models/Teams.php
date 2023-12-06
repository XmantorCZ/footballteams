<?php

namespace models;

class Teams extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'teams';
    protected $primary = 'id';

    protected $fieldConf = [

        'fullname' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'teamid' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
        ],
        'teamcolor' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'name' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'league' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'matches' => [
            'type' => 'VARCHAR256',
            'nullable' =>  false,
            'default' => '0'
        ],
        'goals' => [
            'type' => 'VARCHAR256',
            'nullable' =>  false,
            'default' => '0'
        ],
        'assists' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
            'default' => '0'
        ],
        'yellows' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
            'default' => '0'
        ],
        'reds' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
            'default' => '0'
        ],
    ];

}