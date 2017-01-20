<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use InvalidArgumentException;
use Selami\Entity\Interfaces\DataTypeInterface;

class Uuid extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_FORMAT';

    protected static $defaults = [
        'default'   => '00000000-0000-0000-0000-000000000000'
    ];

    /**
     * Uuid constructor.
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
        if (!preg_match(
            '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/',
            $this->datum
        )
        ) {
            $this->errorMessageTemplate = self::DATA_FORMAT_ERROR;
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
