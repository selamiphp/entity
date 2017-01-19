<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use InvalidArgumentException;

class Html extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';
    const DATA_LENGTH       = 'Assertion failed for value "%s" for "%s" : INVALID_TEXT_LENGTH';

    protected static $defaults = [
        'default'   => null
    ];

    /**
     * Text constructor.
     * @param string $key
     * @param mixed $datum
     * @param array $options
     * @throws validArgumentException
     */
    public function __construct(string $key, $datum, array $options = [])
    {
        $this->key = $key;
        $this->datum = $datum;
        $this->checkValidOptions($options);
        $this->options = array_merge(self::$defaults, $options);
    }
    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        $this->isString();
        return true;
    }

    private function isString()
    {
        if (!is_string($this->datum)) {
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
            return (string) $this->datum;
        } catch (InvalidArgumentException $e) {
            return $this->options['default'];
        }
    }
}
