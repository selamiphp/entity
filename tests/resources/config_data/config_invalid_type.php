<?php

if (!function_exists('testTypeErrorSampleFunction')){
    function testTypeErrorSampleFunction (int $parameter)
    {

    }
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
            'type' => testTypeErrorSampleFunction('int'),
            'length' => 3,

        ],
        'is_active' => [
            'type' => 'enum',
            'options' => [0,1]
        ]
    ]
];