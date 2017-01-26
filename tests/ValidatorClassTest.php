<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Selami\Entity\Validator;
use InvalidArgumentException;

class MyValidatorClass extends TestCase
{
    public $validSchema1 = [
        'id' => [
            'type' => 'integer'
        ],
        'email' => [
            'type' => 'email'
        ],
        'is_active' => [
            'type'  => 'enum',
            'values' => ['0', '1']
        ]
    ];
    public $validSchema2 = [
        'id' => [
            'type' => 'integer'
        ],
        'email' => [
            'type' => 'email'
        ],
        'is_active' => [
            'type'  => 'enum',
            'values' => ['0', '1']
        ],
        'children' => [
            '_many' => [
                'name' => ['type' => 'text'],
                'education' => [
                    '_many' => [
                        'school'    => ['type' => 'text'],
                        'city'      => ['type' => 'text'],
                        'year'      => ['type' => 'integer']
                    ]
                ]
            ]
        ]
    ];

    public $validData1 = [
        'id'        => 1,
        'email'     => 'selami@github.com',
        'is_active' => '1'
    ];

    public $validData2 = [
        'id'        => 1,
        'email'     => 'selami@github.com',
        'is_active' => '1',
        'children'  => [
            [
                'name' => 'Jon',
                'education' => [
                     [
                         'school'    => 'Yıldırım Beyazıt',
                         'city'      => 'Bursa',
                         'year'      => 1996
                     ]
                ]
            ],
            [
                'name' => 'Jane',
                'education' => [
                    [
                        'school'    => 'Ankara University',
                        'city'      => 'Ankara',
                        'year'      => 2003

                    ]
                ]
            ]
        ]
    ];


    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForCheckTypeMissingProperty()
    {
        $properties = [
            '_type' => 'integer'
        ];
        $method = new ReflectionMethod(Validator::class, 'checkType');
        $method->setAccessible(true);
        $method->invoke(new Validator(), 'key', $properties);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionForCheckTypeInvalidDataType()
    {
        $properties = [
            'type' => 'nteger'
        ];
        $method = new ReflectionMethod(Validator::class, 'checkType');
        $method->setAccessible(true);
        $method->invoke(new Validator(), 'key', $properties);
    }

    /**
     * @test
     */
    public function shouldTReturnTrueForCheckType()
    {
        $properties = [
            'type' => 'integer'
        ];
        $method = new ReflectionMethod(Validator::class, 'checkType');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke(new Validator(), 'key', $properties));
    }

    /**
     * @test
     */
    public function shouldAssertTrueForAssertDocItem()
    {
        $constraint = new Validator();
        $assert = $constraint->assertDocItem('key', 'selami@github.com', ['type' => 'email']);
        $this->assertTrue($assert);
    }

    /**
     * @test
     */
    public function shouldReturnErrorForAssertDocItem()
    {
        $constraint = new Validator();
        $assert = $constraint->assertDocItem('key', 'selami@t@github.com', ['type' => 'email']);
        $this->assertContains('INVALID_MAIL_ADDRESS_FORMAT', $assert);
    }

    /**
     * @test
     */
    public function shouldAssertTrueForAssertDoc()
    {
        $constraint = new Validator();
        $assert = $constraint->assertDoc($this->validSchema1, $this->validData1);
        $this->assertArrayHasKey('id', $assert);
        $this->assertArrayHasKey('email', $assert);
        $this->assertArrayHasKey('is_active', $assert);
        $this->assertSame(1, $assert['id']);
        $this->assertSame('selami@github.com', $assert['email']);
        $this->assertSame('1', $assert['is_active']);
    }

    /**
     * @test
     */
    public function shouldAssertTrueForNestedAssertDoc()
    {
        $constraint = new Validator();
        $assert = $constraint->assertDoc($this->validSchema2, $this->validData2);

        $this->assertArrayHasKey('id', $assert);
        $this->assertArrayHasKey('email', $assert);
        $this->assertArrayHasKey('is_active', $assert);
        $this->assertSame(1, $assert['id']);
        $this->assertSame('selami@github.com', $assert['email']);
        $this->assertSame('1', $assert['is_active']);
        $this->assertArrayHasKey(0, $assert['children']);
        $this->assertArrayHasKey('name', $assert['children'][0]);
        $this->assertSame('Jon', $assert['children'][0]['name']);

        $this->assertArrayHasKey(1, $assert['children']);
        $this->assertArrayHasKey('name', $assert['children'][1]);
        $this->assertSame('Jane', $assert['children'][1]['name']);

        $this->assertArrayHasKey('education', $assert['children'][0]);
        $this->assertArrayHasKey('school', $assert['children'][0]['education'][0]);
        $this->assertArrayHasKey('city', $assert['children'][0]['education'][0]);
        $this->assertArrayHasKey('year', $assert['children'][0]['education'][0]);
        $this->assertSame('Yıldırım Beyazıt', $assert['children'][0]['education'][0]['school']);
        $this->assertSame('Bursa', $assert['children'][0]['education'][0]['city']);
        $this->assertSame(1996, $assert['children'][0]['education'][0]['year']);
    }


    /**
     * @test
     * @dataProvider invalidDataProvider
     * @param array $invalidData
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowExceptionForAssertDoc($invalidData)
    {
        $constraint = new Validator();
        $constraint->assertDoc($this->validSchema1, $invalidData);
    }

    public function invalidDataProvider()
    {
        return [
           [
               ['id' => 2, 'non_existing_field' => 'some_text'],
               ['id' => 2, 'email' => 'selami@github.com', 'is_active' => 1]
           ]
        ];
    }

    /**
     * @test
     * @dataProvider invalidSchemaProvider
     * @param array $invalidSchema
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowExceptionForAssertDocForInvalidSchema($invalidSchema)
    {
        $constraint = new Validator();
        $constraint->assertDoc($invalidSchema, $this->validData1);
    }

    public function invalidSchemaProvider()
    {
        return [
            [
                'id' => [
                    'type' => 'integer'
                ],
                'email' => [
                    'type' => 'email'
                ],
                'is_active' => [
                    'type'  => 'non_existed_data_type',
                    'values' => ['0', '1']
                ]
            ],
            [
                'id' => [
                    'type' => 'integer'
                ],
                'email' => [
                    'type' => 'email'
                ],
                'is_active' => [
                    'values' => ['0', '1']
                ]
            ]
        ];
    }
}
