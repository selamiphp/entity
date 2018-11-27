# Selami Entity

Framework agnostic entity/value object library to assert variable types and values for a model defined using JSON Schema standard (draft-07 and draft-06) written in PHP 7.2

[![Build Status](https://api.travis-ci.org/selamiphp/entity.svg?branch=master)](https://travis-ci.org/selamiphp/entity) [![Coverage Status](https://coveralls.io/repos/github/selamiphp/entity/badge.svg?branch=master)](https://coveralls.io/github/selamiphp/entity?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/selamiphp/entity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/selamiphp/entity/) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/d564565dbc754376a9d022731ec1af75)](https://www.codacy.com/app/mehmet/entity?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=selamiphp/entity&amp;utm_campaign=Badge_Grade) [![Latest Stable Version](https://poser.pugx.org/selami/entity/v/stable)](https://packagist.org/packages/selami/entity) [![Total Downloads](https://poser.pugx.org/selami/entity/downloads)](https://packagist.org/packages/selami/entity) [![Latest Unstable Version](https://poser.pugx.org/selami/entity/v/unstable)](https://packagist.org/packages/selami/entity) [![License](https://poser.pugx.org/selami/entity/license)](https://packagist.org/packages/selami/entity)


## Motivation

This library is a kind of helper library to generate your own Entity or Value Object models using JSON Schema. It is not expected to use classes in this library directly.


## Installation 

```bash
composer require selami/entity
```

## Usage

Say you have a JSON Schema file at ./models/profile.json. See the Value Object model [ file content](https://github.com/selamiphp/entity/blob/master/tests/resources/test-schema-value-object.json).

### Value Objects ([See explaination](https://martinfowler.com/bliki/ValueObject.html))

- Objects created using ValueObject are [Immutable](https://en.wikipedia.org/wiki/Immutable_object). This means only data injecting point is its constructor. 
- It validates data on object creation
- If validation fails it throws InvalidArgumentException.

```php
<?php
declare(strict_types=1);

use Selami\Entity\ValueObject;
use stdClass;

$data = new stdClass();
$data->name = 'Kedibey';
$data->age = 11;
$data->email = 'kedibey@world-of-wonderful-cats-yay.com';
$data->website = 'orld-of-wonderful-cats-yay.com';
$data->location = new stdClass();
$data->location->country = 'TR';
$data->location->address = 'Kadıköy, İstanbul';
$data->available_for_hire = true;
$data->interests = ['napping', 'eating', 'bird gazing'];
$data->skills = [];
$data->skills[0] = new stdClass();
$data->skills[0]->name = 'PHP';
$data->skills[0]->value = 0;

$valueObject = ValueObject::createFromJsonFile('./models/profile.json', $data);

```

### Entities

- Entities require id property.
- Entities are not Immutable.
- Entities are not automatically validated on object creation.
- When validation failed it throws InvalidArgumentException.
- It is possible to partially validate Entities.
- It can be used to form data validation, data validation before persisting it, etc...

```php
<?php
declare(strict_types=1);

use Selami\Entity\Entity;
use stdClass;
use Ramsey\Uuid\Uuid

$id = Uuid::uuid4()->toString();
$entity = Entity::createFromJsonFile('./models/profile.json', $id);
$entity->name = 'Kedibey';
$entity->age = 11;
$entity->email = 'kedibey@world-of-wonderful-cats-yay.com';
$entity->website = 'world-of-wonderful-cats-yay.com';
$entity->location = new stdClass();
$entity->location->country = 'TR';
$entity->location->address = 'Kadıköy, İstanbul';
$entity->available_for_hire = true;
$entity->interests = ['napping', 'eating', 'bird gazing'];
$entity->skills = [];
$entity->skills[0] = new stdClass();
$entity->skills[0]->name = 'PHP';
$entity->skills[0]->value = 0;

$entity->validate();

```

#### Partial Validation

```php
<?php
declare(strict_types=1);

use Selami\Entity\Entity;
use stdClass;
use Ramsey\Uuid\Uuid

$id = Uuid::uuid4()->toString();
$entity = Entity::createFromJsonFile('./models/profile.json', $id);
$entity->name = 'Kedibey';
$entity->age = 11;
$entity->email = 'kedibey@world-of-wonderful-cats-yay.com';
$entity->website = 'world-of-wonderful-cats-yay.com';

$partiallyValidateFields = ['name', 'age', 'email', 'website'];

$entity->validatePartially(partiallyValidateFields);

```


### Example of intended usage

#### Value Object Example

```php
<?php
declare(strict_types=1);

namespace MyLibrary\ValueObject;

use Selami\Entity\Interfaces\ValueObjectInterface;
use stdClass;
use Selami\Entity\Model;
use Selami\Entity\ValueObjectTrait;

final ProfileValueObject implements ValueObjectInterface
{
	private static schemaFile = 'models/profile.json';

	use ValueObject;    
    
	public static function create(stdClass $data) : ValueObjectInterface
	{
		$model = Model::createFromJsonFile(self::$schemaFile);
		return static($model, $data)
	}
}
```


#### Entity Example

```php
<?php
declare(strict_types=1);

namespace MyLibrary\Entity;

use Selami\Entity\Interfaces\EntityInterface;
use stdClass;
use Selami\Entity\Model;
use Selami\Entity\EntityTrait;

final ProfileEntity implements EntityInterface
{
	private static schemaFile = 'models/profile.json';

	use EntityTrait;    
    
	public static function create(string $id, ?stdClass $data=null) : EntityInterface
	{
		$model = Model::createFromJsonFile(self::$schemaFile);
		return static($model, $id, $data)
	}
}

