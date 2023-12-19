<?php

namespace models;

class Leagues extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'leagues';
    protected $primary = 'id';

    protected $fieldConf = [

        'fullname' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'league_id' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
        ],
        'league_color' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'number_of_teams' => [
            'type' => 'TEXT',
            'nullable' => false,
        ],
        'state' => [
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