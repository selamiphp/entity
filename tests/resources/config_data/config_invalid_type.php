<?php


function test_type_error_sample_function (int $parameter)
{

}

return [
    'schema' => [
        'id' => [
            'type' => 'int',
            'length' => 3,
            'min' => 0,
            'max' => 999
        ],
        'age' => [
            'type' => test_type_error_sample_function('int'),
            'length' => 3,

        ],
        'is_active' => [
            'type' => 'enum',
            'options' => [0,1]
        ]
    ]
];