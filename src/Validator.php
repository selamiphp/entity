<?php


namespace Selami\Entity;

use Selami\Entity\DataType;
use InvalidArgumentException;

class Validator
{
    static protected $constraints = [
        'Boolean'   => DataType\Boolean::class,
        'Date'      => DataType\Date::class,
        'Double'    => DataType\Double::class,
        'Email'     => DataType\Email::class,
        'Enum'      => DataType\Enum::class,
        'FilePath'  => DataType\FilePath::class,
        'Html'      => DataType\Html::class,
        'Integer'   => DataType\Integer::class,
        'Ipv4'      => DataType\Ipv4::class,
        'Ipv6'      => DataType\Ipv6::class,
        'MacAddress'    => DataType\MacAddress::class,
        'PhoneNumber'   => DataType\PhoneNumber::class,
        'Regex'     => DataType\Regex::class,
        'Slug'      => DataType\Slug::class,
        'Text'      => DataType\Text::class,
        'Url'       => DataType\Url::class,
        'Uuid'      => DataType\Uuid::class
    ];

    static protected $validTypes = [
        'Boolean'   => 'boolean',
        'Date'      => 'string',
        'Double'    => 'float',
        'Email'     => 'string',
        'Enum'      => 'string',
        'FilePath'  => 'string',
        'Html'      => 'string',
        'Integer'   => 'integer',
        'Ipv4'      => 'string',
        'Ipv6'      => 'string',
        'MacAddress'    => 'string',
        'PhoneNumber'   => 'string',
        'Regex'     => 'string',
        'Slug'      => 'string',
        'Text'      => 'string',
        'Url'       => 'string',
        'Uuid'      => 'string'
    ];

    /**
     * @param string $itemKey
     * @param array $properties
     * @return bool
     * @throws InvalidArgumentException
     */
    private function checkType(string $itemKey, array $properties)
    {
        if (!array_key_exists('type', $properties)) {
            throw new InvalidArgumentException(sprintf('%s must have "type" property.', $itemKey));
        }
        if (!array_key_exists(upperCamelCase($properties['type']), self::$constraints)) {
            throw new InvalidArgumentException(sprintf('%s is not valid DataType.', $properties['type']));
        }
        return true;
    }

    /**
     * @param string $itemKey
     * @param mixed $item
     * @param array $properties
     * @return bool|string
     * @throws InvalidArgumentException
     */
    public function assertDocItem(string $itemKey, $item, array $properties)
    {
        $this->checkType($itemKey, $properties);
        try {
            $dataType = self::$constraints[upperCamelCase($properties['type'])];
            unset($properties['type']);
            $constraint = new $dataType($itemKey, $item, $properties);
            return $constraint->assert();
        } catch (InvalidArgumentException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Validate given documents
     *
     * @param array $schema
     * @param array $myDoc
     * @param string $myKey
     * @return array
     * @throws InvalidArgumentException
     */
    public function assertDoc(array $schema, array $myDoc, string $myKey = null)
    {
        $myKeys = array_keys($myDoc);
        foreach ($myKeys as $key) {
            $myDoc_key_type = getType($myDoc[$key]);
            $vKey = $key;
            if ($myKey !== null) {
                $vKey = $myKey.'.'.$key;
            }
            // Does doc has an array key that does not exist in model definition.
            if (!isset($schema[$key])) {
                $message = sprintf('Error for key "%s" that does not exist in the model', $vKey);
                throw new InvalidArgumentException($message);
            } // Is the value of the array[key] again another array?
            elseif ($myDoc_key_type === 'array') {
                // Validate this array too.
                $myDoc[$key] = $this->assertDoc($schema[$key], $myDoc[$key], $vKey);
                if (getType($myDoc[$key]) !== 'array') {
                    return $myDoc[$key];
                }
            } // Is the value of the array[key] have same variable type
            //that stated in the definition of the model array.
            elseif ($myDoc_key_type !== self::$validTypes[upperCamelCase($schema[$key]['type'])]) {
                $message = sprintf(
                    'Error for key "%s", %s given but it must be %s',
                    $vKey,
                    $myDoc_key_type,
                    self::$validTypes[upperCamelCase($schema[$key]['type'])]
                );
                throw new InvalidArgumentException($message);
            } else {
                $assertedItem = $this->assertDocItem($vKey, $myDoc[$key], $schema[$key]);
                if($assertedItem !== true) {
                    throw new InvalidArgumentException($assertedItem);
                }
            }
        }
        return $myDoc;
    }
}
