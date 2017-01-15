<?php

return [
    'schema' => [
        'id' => [
            'type' => 'int',
            'length' => 3,
            'min' => 0,
            'max' => 999
        ],
        'age' => [
            'type' => 'int',
            'length' => \assert('2 < 1'),

        ],
        'is_active' => [
            'type' => 'enum',
            'options' => [0,1]
        ]
    ]
];