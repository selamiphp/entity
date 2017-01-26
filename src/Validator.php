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
     * Validate given documents
     *
     * @param array $schema
     * @param array $myDoc
     * @param array $myKey
     * @return array
     * @throws InvalidArgumentException
     */
    public function assertDoc(array $schema, array $myDoc, array $myKey = null)
    {
        $myKeys = array_keys($myDoc);
        foreach ($myKeys as $key) {
            $myDoc[$key] = $this->assertItem($schema, $myDoc, $key, $myKey);
        }
        return $myDoc;
    }

    /**
     * @param array $schema
     * @param array $myDoc
     * @param string $key
     * @param array $myKey
     * @return array|bool|string
     * @throws InvalidArgumentException;
     */
    private function assertItem(array $schema, array $myDoc, string $key, array $myKey = null)
    {
        $vKey = is_array($myKey) ? $myKey + [$key] : [$key];
        $tmp = $this->isMulti($schema, $myDoc[$key], $key, $vKey);
        if ($tmp !== false && is_array($tmp)) {
            return $tmp;
        }
        $this->doesSchemaHasKey($schema, $key, $vKey);

        $myDocKeyType = getType($myDoc[$key]);
         $this->checkValueType($myDocKeyType, $schema[$key], $vKey);

        $assertedItem = $this->assertDocItem($key, $myDoc[$key], $schema[$key]);
        if ($assertedItem !== true) {
            throw new InvalidArgumentException($assertedItem);
        }
        return $myDoc[$key];
    }

    private function isMulti(array $schema, $myDoc, $key, array $vKey)
    {
        if (array_key_exists($key, $schema) && is_array($schema[$key]) && array_key_exists('_many', $schema[$key])) {
            $newDoc = [];
            foreach ($myDoc as $mKey => $item) {
                $tmpvKey = $vKey + [$mKey];
                $newDoc[] = $this->assertDoc($schema[$key]['_many'], $item, $tmpvKey);
            }
            return $newDoc;
        }
        return false;
    }

    private function doesSchemaHasKey(array $schema, $key, $vKey)
    {
        // Does doc has an array key that does not exist in model definition.
        if (!isset($schema[$key])) {
            $message = sprintf('Error for key "%s" that does not exist in the model', implode('.', $vKey));
            throw new InvalidArgumentException($message);
        }
    }

    // Is the value of the array[key] have same variable type
    //that stated in the definition of the model array.
    private function checkValueType($myDocKeyType, $schemaKey, $vKey)
    {
        if (
            is_array($schemaKey)
            && array_key_exists('type', $schemaKey)
            && $myDocKeyType  !== self::$validTypes[upperCamelCase($schemaKey['type'])]
        ) {
            $message = sprintf(
                'Error for key "%s": %s value given but it must have been %s',
                implode('.', $vKey),
                $myDocKeyType,
                self::$validTypes[upperCamelCase($schemaKey['type'])]
            );
            throw new InvalidArgumentException($message);
        }
    }
}
