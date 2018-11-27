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
        $valueObject = new stdClass();
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

        $valObject = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $valueObject
        );

        $this->assertTrue($valObject->validate());
        $arrayFromJson = json_decode(json_encode($valObject), true);
        $this->assertEquals(31, $arrayFromJson['age']);
        $this->assertTrue(isset($valObject->name));
    }

    /**
     * @test
     */
    public function shouldValidatePartiallySuccessfully() : void
    {
        $valueObject = new stdClass();
        $valueObject->name = 'John Doe';
        $valueObject->age = 31;
        $requiredFields = ['name', 'age'];
        $valObject = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $valueObject
        );

        $this->assertTrue($valObject->validatePartially($requiredFields));
        $requiredFields = ['name', 'age', 'email'];
        $this->expectException(\Selami\Entity\Exception\InvalidArgumentException::class);
        $valObject->validatePartially($requiredFields);
    }
    /**
     * @test
     */
    public function shouldCompareTwoValueObjectSuccessfully() : void
    {
        $object1 = new stdClass();
        $object2 = new stdClass();
        $object3 = new stdClass();

        $object1->name = 'Kedibey';
        $object1->details = new stdClass();
        $object1->details->age = 11;
        $object1->details->type = 'Angora';

        $object2->name = 'Kedibey';
        $object2->details = new stdClass();
        $object2->details->age = 11;
        $object2->details->type = 'Angora';

        $object3->name = 'Kedibey';
        $object3->details = new stdClass();
        $object3->details->age = 11;
        $object3->details->type = 'Van';

        $valueObject1 = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $object1
        );
        $valueObject2 = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $object2
        );
        $valueObject3 = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $object3
        );
        $this->assertTrue($valueObject1->equals($valueObject2));
        $this->assertFalse($valueObject1->equals($valueObject3));
    }




    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidArgumentException
     */
    public function shouldFailForRequiredInput() : void
    {
        $object = new stdClass();
        $object->name = 'John Doe';
        $object->age = 31;
        $object->email = "john@example.com";
        $object->website = null;

        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema.json');
        $valueObject = new ValueObject($model, $object);
        $valueObject->validate();
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\UnexpectedValueException
     */
    public function shouldFailForAModelFileDoesNotExist() : void
    {
        ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-no-file.json', new stdClass());
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\BadMethodCallException
     */
    public function shouldFailForSettingNewValue() : void
    {
        $valueObject = ValueObject::createFromJsonFile(__DIR__.'/../resources/test-schema-value-object.json', new stdClass());
        $valueObject->name = 'Kedibey';
    }
    /**
     * @test
     * @expectedException \Selami\Entity\Exception\BadMethodCallException
     */
    public function shouldFailForUnsettingAValue() : void
    {
        $object = new stdClass();
        $object->name='Mırmır';

        $valueObject = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $object
        );
        unset($valueObject->name);
    }
}
