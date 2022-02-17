<?php
namespace footballteams\data;


class SlaviaPraha extends \DB\Cortex
{
    protected $db = 'DB';
    protected $table = 'slaviapraha';
    protected $primary = 'id';

    protected $fieldConf = [

        'cislodresu' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'Jmeno' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'Prijmeni' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'Narod' => [
            'type' => 'TEXT',
            'nullable' => false
        ],
        'Pozice' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'Stav' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'Hodnota' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'Zapasy' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'Goly' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'Asistence' => [
            'type' => 'VARCHAR256',
            'nullable' => false,
        ],
        'ZK' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],
        'CK' => [
            'type' => 'VARCHAR256',
            'nullable' => false
        ],


    ];

}