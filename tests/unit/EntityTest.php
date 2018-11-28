<?php

use Selami\Entity\Entity;
use Selami\Entity\Model;
use Ramsey\Uuid\Uuid;

class EntityTest extends \Codeception\Test\Unit
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
    public function shouldReturnEntityObjectSuccessfully() : void
    {
        $id = Uuid::uuid4()->toString();
        $entity = Entity::createFromJsonFile(__DIR__.'/../resources/test-schema.json', $id);
        $entity->name = 'John Doe';
        $entity->age = 18;
        $entity->email = "john@example.com";
        $entity->website = null;
        $entity->location = new stdClass();
        $entity->location->country = 'US';
        $entity->location->address = 'Sesame Street, no. 5';
        $entity->available_for_hire = true;
        $entity->interests = ['oop', 'solid', 'tdd', 'ddd'];
        $item =new stdClass();
        $item->name = 'PHP';
        $item->value = 100;
        $entity->skills = [$item];
        $this->assertEquals($id, $entity->entityId());
        $this->assertTrue($entity->validate());
        $arrayFromJson = json_decode(json_encode($entity), true);
        $this->assertEquals(18, $arrayFromJson['age']);
        $this->assertTrue(isset($entity->name));
        unset($entity->name);
        $this->assertFalse(isset($entity->name));
    }

    /**
     * @test
     */
    public function shouldValidatePartiallySuccessfully() : void
    {
        $id = Uuid::uuid4()->toString();
        $entity = Entity::createFromJsonFile(__DIR__.'/../resources/test-schema.json', $id);
        $entity->name = 'John Doe';
        $entity->age = 18;
        $requiredFields = ['name', 'age'];
        $this->assertTrue($entity->validatePartially($requiredFields));
        $requiredFields = ['name', 'age', 'email'];
        $this->expectException(\Selami\Entity\Exception\InvalidArgumentException::class);
        $entity->validatePartially($requiredFields);
    }

    /**
     * @test
     */
    public function shouldCompareTwoEntityObjectSuccessfully() : void
    {
        $id1 = Uuid::uuid4()->toString();
        $id2 = $id1;
        $id3 = Uuid::uuid4()->toString();

        $entity1 = Entity::createFromJsonFile(__DIR__.'/../resources/test-schema.json', $id1);
        $entity2 = Entity::createFromJsonFile(__DIR__.'/../resources/test-schema.json', $id2);
        $entity3 = Entity::createFromJsonFile(__DIR__.'/../resources/test-schema.json', $id3);

        $entity1->name = 'Kedibey';
        $entity1->age = 11;
        $entity1->available_for_hire = true;

        $entity2->name = 'Kedibey';
        $entity2->age = 11;
        $entity2->available_for_hire = true;

        $entity3->name = 'Kedibey';
        $entity3->age = 11;
        $entity3->available_for_hire = true;

        $this->assertTrue($entity1->equals($entity2));
        $this->assertFalse($entity1->equals($entity3));
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidArgumentException
     */
    public function shouldFailForRequiredInput() : void
    {
        $id = Uuid::uuid4()->toString();
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema.json');
        $entity = new Entity($model, $id);
        $entity->name = 'John Doe';
        $entity->age = 18;
        $entity->email = "john@example.com";
        $entity->website = null;
        $entity->validate();
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\SettingValueForInvalidPropertyException
     */
    public function shouldFailForInvalidInputParameter() : void
    {
        $id = Uuid::uuid4()->toString();
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema.json');
        $entity = new Entity($model, $id);
        $entity->name = 'John Doe';
        $entity->age = 18;
        $entity->email = "john@example.com";
        $entity->website = null;
        $entity->invalidInput = true;
    }

    /**
     * @test
     * @expectedException \Selami\Entity\Exception\OverridingIdentityOfEntityException
     */
    public function shouldFailWhenOverridiingId() : void
    {
        $id = Uuid::uuid4()->toString();
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema.json');
        $entity = new Entity($model, $id);
        $entity->name = 'John Doe';
        $entity->age = 18;
        $entity->email = "john@example.com";
        $entity->website = null;
        $entity->id = 1;
    }


    /**
     * @test
     * @expectedException \Selami\Entity\Exception\InvalidArgumentException
     */
    public function shouldFailForAModelFileDoesNotExist() : void
    {
        $id = Uuid::uuid4()->toString();
        Entity::createFromJsonFile(__DIR__.'/../resources/test-schema-no-file.json', $id);
    }
}
