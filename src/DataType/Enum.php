<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use InvalidArgumentException;

class Enum extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';

    protected static $defaults = [
        'default'   => false,
        'values'    => null
    ];


    /**
     * Enum constructor.
     * @param string $key
     * @param mixed $datum
     * @param array $options
     * @throws InvalidArgumentException;
     *
     */
    public function __construct(string $key, $datum, array $options = [])
    {
        $this->key = $key;
        $this->datum = $datum;
        $this->checkValidOptions($options);
        $this->options = array_merge(self::$defaults, $options);
        if ($this->options['values'] === null
            || !is_array($this->options['values'])
            || count($this->options['values']) < 3
        ) {
            throw new InvalidArgumentException('$options[values] must be an array has at least two elements');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        try {
            if (!in_array($this->datum, $this->options['values'], true)) {
                $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
                $this->throwException();
            }
        } catch (\TypeError $e) {
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
