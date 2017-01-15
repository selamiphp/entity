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
            'length' => intdiv(PHP_INT_MIN, 0.5),

        ],
        'is_active' => [
            'type' => 'enum',
            'options' => [0,1]
        ]
    ]
];