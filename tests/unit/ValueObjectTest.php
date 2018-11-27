<?php

use Selami\Entity\ValueObject;
use Selami\Entity\Model;

class ValueObjectTest extends \Codeception\Test\Unit
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
        $valueObject = ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-value-object.json');
        $valueObject->name = 'John Doe';
        $valueObject->age = 31;
        $valueObject->email = "john@example.com";
        $valueObject->website = null;
        $valueObject->location = new stdClass();
        $valueObject->location->country = 'US';
        $valueObject->location->address = 'Sesame Street, no. 5';
        $valueObject->available_for_hire = true;
        $valueObject->interests = ['oop', 'solid', 'tdd', 'ddd'];
        $item =new stdClass();
        $item->name = 'PHP';
        $item->value = 100;
        $valueObject->skills = [$item];
        $this->assertTrue($valueObject->validate());
        $arrayFromJson = json_decode($valueObject, true);
        $this->assertEquals(31, $arrayFromJson['age']);
        $this->assertTrue(isset($valueObject->name));
        unset($valueObject->name);
        $this->assertFalse(isset($valueObject->name));
    }

    /**
     * @test
     */
    public function shouldValidatePartiallySuccessfully() : void
    {
        $valueObject = ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-value-object.json');
        $valueObject->name = 'John Doe';
        $valueObject->age = 31;
        $requiredFields = ['name', 'age'];
        $this->assertTrue($valueObject->validatePartially($requiredFields));
        $requiredFields = ['name', 'age', 'email'];
        $this->expectException(\Selami\Entity\Exception\InvalidArgumentException::class);
        $valueObject->validatePartially($requiredFields);
    }
    /**
     * @test
     */
    public function shouldCompareTwoValueObjectSuccessfully() : void
    {
        $valueObject1 = ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-value-object.json');
        $valueObject2 = ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-value-object.json');
        $valueObject3 = ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-value-object.json');

        $valueObject1->name = 'Kedibey';
        $valueObject1->details = new stdClass();
        $valueObject1->details->age = 11;
        $valueObject1->details->type = 'Angora';

        $valueObject2->name = 'Kedibey';
        $valueObject2->details = new stdClass();
        $valueObject2->details->age = 11;
        $valueObject2->details->type = 'Angora';

        $valueObject3->name = 'Kedibey';
        $valueObject3->details = new stdClass();
        $valueObject3->details->age = 11;
        $valueObject3->details->type = 'Van';

        $this->assertTrue($valueObject1->equals($valueObject2));
        $this->assertFalse($valueObject1->equals($valueObject3));
    }




    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidArgumentException
     */
    public function shouldFailForRequiredInput() : void
    {
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema.json');
        $valueObject = new ValueObject($model);
        $valueObject->name = 'John Doe';
        $valueObject->age = 31;
        $valueObject->email = "john@example.com";
        $valueObject->website = null;
        $valueObject->validate();
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\UnexpectedValueException
     */
    public function shouldFailForAModelFileDoesNotExist() : void
    {
        ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-no-file.json');
    }
}
