<?php

namespace models;

class Uzivatele extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'uzivatele';
    protected $primary = 'id';

    protected $fieldConf = [

        'nickname' => [
            'type' => 'TEXT',
            'nullable' => false,
            'index' => 'true',
            'unique' => 'true',
        ],
        'password' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'role' => [
            'type' => 'VARCHAR256',
            'nullable' =>  false,
            'default' => '1'
        ],
        'date' => [
            'type' => 'DATE',
            'nullable' => false
        ]
    ];
}