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
    public function shouldCompareTwoValueObjectSuccessfully() : void
    {
        $object1 = new stdClass();
        $object2 = new stdClass();
        $object3 = new stdClass();

        $object1->name = 'Kedibey';
        $object1->age = 11;
        $object1->email = 'kedibey@world-of-wonderful-cats-yay.com';
        $object1->website = 'orld-of-wonderful-cats-yay.com';
        $object1->location = new stdClass();
        $object1->location->country = 'TR';
        $object1->location->address = 'Kadıköy, İstanbul';
        $object1->available_for_hire = true;
        $object1->interests = ['napping', 'eating', 'bird gazing'];
        $object1->skills = [];
        $object1->skills[0] = new stdClass();
        $object1->skills[0]->name = 'PHP';
        $object1->skills[0]->value = 0;

        $object2->name = 'Kedibey';
        $object2->age = 11;
        $object2->email = 'kedibey@world-of-wonderful-cats-yay.com';
        $object2->website = 'orld-of-wonderful-cats-yay.com';
        $object2->location = new stdClass();
        $object2->location->country = 'TR';
        $object2->location->address = 'Kadıköy, İstanbul';
        $object2->available_for_hire = true;
        $object2->interests = ['napping', 'eating', 'bird gazing'];
        $object2->skills = [];
        $object2->skills[0] = new stdClass();
        $object2->skills[0]->name = 'PHP';
        $object2->skills[0]->value = 0;

        $object3->name = 'Kedibey';
        $object3->age = 12;
        $object3->email = 'kedibey@world-of-wonderful-cats-yay.com';
        $object3->website = 'orld-of-wonderful-cats-yay.com';
        $object3->location = new stdClass();
        $object3->location->country = 'TR';
        $object3->location->address = 'Kadıköy, İstanbul';
        $object3->available_for_hire = true;
        $object3->interests = ['napping', 'eating', 'bird gazing'];
        $object3->skills = [];
        $object3->skills[0] = new stdClass();
        $object3->skills[0]->name = 'PHP';
        $object3->skills[0]->value = 0;

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
     * @expectedException \Selami\Entity\Exception\InvalidArgumentException
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
        $object1 = new stdClass();
        $object1->name = 'Kedibey';
        $object1->age = 11;
        $object1->email = 'kedibey@world-of-wonderful-cats-yay.com';
        $object1->website = 'orld-of-wonderful-cats-yay.com';
        $object1->location = new stdClass();
        $object1->location->country = 'TR';
        $object1->location->address = 'Kadıköy, İstanbul';
        $object1->available_for_hire = true;
        $object1->interests = ['napping', 'eating', 'bird gazing'];
        $object1->skills = [];
        $object1->skills[0] = new stdClass();
        $object1->skills[0]->name = 'PHP';
        $object1->skills[0]->value = 0;
        $valueObject = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $object1
        );
        $valueObject->name = 'Kedibey';
    }
    /**
     * @test
     * @expectedException \Selami\Entity\Exception\BadMethodCallException
     */
    public function shouldFailForUnsettingAValue() : void
    {
        $object1 = new stdClass();
        $object1->name = 'Kedibey';
        $object1->age = 11;
        $object1->email = 'kedibey@world-of-wonderful-cats-yay.com';
        $object1->website = 'orld-of-wonderful-cats-yay.com';
        $object1->location = new stdClass();
        $object1->location->country = 'TR';
        $object1->location->address = 'Kadıköy, İstanbul';
        $object1->available_for_hire = true;
        $object1->interests = ['napping', 'eating', 'bird gazing'];
        $object1->skills = [];
        $object1->skills[0] = new stdClass();
        $object1->skills[0]->name = 'PHP';
        $object1->skills[0]->value = 0;
        $valueObject = ValueObject::createFromJsonFile(
            __DIR__.'/../resources/test-schema-value-object.json',
            $object1
        );
        unset($valueObject->name);
    }
}
