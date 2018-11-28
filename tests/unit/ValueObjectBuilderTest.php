<?php

use Selami\Entity\ValueObject;
use Selami\Entity\ValueObjectBuilder;
use Selami\Entity\Model;

class ValueObjectBuilderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function shouldReturnValueObjectSuccessfully() : void
    {
        $valueObject = ValueObjectBuilder::createFromJsonFile(
            __DIR__.'/../resources/test-schema-credit-card-value-object.json'
        )
            ->withCardNumber('5555555555555555')
            ->withCardHolderName('Kedibey')
            ->withExpireDateMonth(8)
            ->withExpireDateYear(24)
            ->withCvvNumber('937')
            ->build();

        $this->assertEquals('Kedibey', $valueObject->cardHolderName);
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidMethodNameException
     */
    public function shouldFailForInvalidMethodName() : void
    {
        ValueObjectBuilder::createFromJsonFile(
            __DIR__.'/../resources/test-schema-credit-card-value-object.json'
        )
            ->cardNumber('5555555555555555')
            ->withCardHolderName('Kedibey')
            ->withExpireDateMonth(8)
            ->withExpireDateYear(24)
            ->withCvvNumber('937')
            ->build();
    }
    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidMethodNameException
     */
    public function shouldFailForInvalidPropertyName() : void
    {
        ValueObjectBuilder::createFromJsonFile(
            __DIR__.'/../resources/test-schema-credit-card-value-object.json'
        )
            ->withCardNumber('5555555555555555')
            ->withCardHoldersName('Kedibey')
            ->withExpireDateMonth(8)
            ->withExpireDateYear(24)
            ->withCvvNumber('937')
            ->build();
    }
    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidArgumentException
     */
    public function shouldFailForAModelFileDoesNotExist() : void
    {
        ValueObjectBuilder::createFromJsonFile(__DIR__.'/../resources/test-schema-no-file.json');
    }
}
