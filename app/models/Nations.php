<?php


namespace models;


class Nations extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'nations';
    protected $primary = 'id';

    protected $fieldConf = [

        'idname' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'name' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'flagname' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
        ],

    ];
}