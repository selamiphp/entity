<?php

use Selami\Entity\Model;
use Selami\Entity\Exception\UnexpectedValueException;

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
        $model = Model::fromJsonFile(__DIR__.'/../resources/test-schema.json');
        $schema = $model->getSchema();
        $this->assertEquals('http://api.example.com/profile.json#', $schema->id());
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     */
    public function shouldFailForAFileThatDoesNotExist() : void
    {
        $model = Model::fromJsonFile(__DIR__.'/../resources/test-schema-does-not-exist.json');
        $model->getSchema();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function shouldFailForAFileThatContainsInvalidJson() : void
    {
        $model = Model::fromJsonFile(__DIR__.'/../resources/test-schema-invalid-json.json');
        $model->getSchema();
    }
}
