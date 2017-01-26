<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Selami\Entity\Validator;
use InvalidArgumentException;

class MyValidatorClass extends TestCase
{
    public $validSchema = [
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

    public $validData = [
        'id'        => 1,
        'email'     => 'mehmet@github.com',
        'is_active' => '1'
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
        $assert = $constraint->assertDocItem('key', 'mehmet@github.com', ['type' => 'email']);
        $this->assertTrue($assert);
    }

    /**
     * @test
     */
    public function shouldReturnErrorForAssertDocItem()
    {
        $constraint = new Validator();
        $assert = $constraint->assertDocItem('key', 'mehme@t@github.com', ['type' => 'email']);
        $this->assertContains('INVALID_MAIL_ADDRESS_FORMAT', $assert);
    }

    /**
     * @test
     */
    public function shouldAssertTrueForAssertDoc()
    {
        $constraint = new Validator();
        $assert = $constraint->assertDoc($this->validSchema, $this->validData);
        $this->assertArrayHasKey('id',$assert);
        $this->assertArrayHasKey('email',$assert);
        $this->assertArrayHasKey('is_active',$assert);
        $this->assertSame(1, $assert['id']);
        $this->assertSame('mehmet@github.com', $assert['email']);
        $this->assertSame('1', $assert['is_active']);
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
        $constraint->assertDoc($this->validSchema, $invalidData);
    }

    public function invalidDataProvider()
    {
       return [
           [
               ['id' => 2, 'non_existing_field' => 'some_text']
           ]
       ];
    }
}
