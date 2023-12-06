<?php


namespace models;


class Players extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'players';
    protected $primary = 'id';

    protected $fieldConf = [

        'name' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'surname' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'nation' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'league' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'team' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'age' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'position' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'numberdress' => [
            'type' => 'VARCHAR256',
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
        'state' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'value' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
    ];
}