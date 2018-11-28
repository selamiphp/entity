<?php

use Selami\Entity\Model;
use Selami\Entity\Exception\InvalidArgumentException;

class ModelTest extends \Codeception\Test\Unit
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
    public function shouldReturnModelObjectSuccessfully() : void
    {
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema.json');
        $schema = $model->getSchema();
        $this->assertEquals('http://api.example.com/profile.json#', $schema->id());

        $jsonSchema = file_get_contents(__DIR__.'/../resources/test-schema.json');
        $tmpSchema = json_decode($jsonSchema);
        $requiredFields = $tmpSchema->required;
        $model = new Model($jsonSchema);
        $this->assertEquals($tmpSchema, $model->getModel());
        $modelRequiredFields = $model->getRequiredFields();
        $this->assertEquals($modelRequiredFields, $requiredFields);
        $schema = $model->getSchema($tmpSchema);
        $this->assertEquals('http://api.example.com/profile.json#', $schema->id());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldFailForAFileThatDoesNotExist() : void
    {
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema-does-not-exist.json');
        $model->getSchema();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function shouldFailForAFileThatContainsInvalidJson() : void
    {
        $model = Model::createFromJsonFile(__DIR__.'/../resources/test-schema-invalid-json.json');
        $model->getSchema();
    }
}
