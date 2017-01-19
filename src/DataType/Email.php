<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use InvalidArgumentException;

class Email extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_MAIL_ADDRESS_FORMAT';

    protected static $defaults = [
        'default'   => ''
    ];


    /**
     * Email constructor.
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
        if (filter_var($this->datum, FILTER_VALIDATE_EMAIL) === false) {
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
