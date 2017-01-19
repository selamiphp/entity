<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use InvalidArgumentException;

class Integer extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';

    protected static $defaults = [
        'default' => 0
    ];


    /**
     * Integer constructor.
     * @param string $key
     * @param mixed $datum
     * @param array $options
     */
    public function __construct(string $key, $datum, array $options = [])
    {
        $this->key = $key;
        $this->datum = $datum;
        $this->options = array_merge(self::$defaults, $options);
    }
    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        if (!is_int($this->datum)) {
            $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
            $this->throwException();
        }
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function normalize()
    {
        try {
            $this->assert();
            return $this->datum;
        } catch (InvalidArgumentException $e) {
            return $this->options['default'];
        }
    }
}
