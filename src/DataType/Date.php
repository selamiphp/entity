<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use DateTime;
use Assert\Assertion;
use BadMethodCallException;
use InvalidArgumentException;

class Date extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_TYPE_ERROR   = 'Error for value "%s" for "%s" : INVALID_TYPE. Must be a date string.';
    const DATA_FORMAT_ERROR = 'Error for value "%s" for "%s": INVALID_DATE_FORMAT';
    const DATA_MIN_ERROR    = 'Error for value "%s" for "%s": MIN_VALUE=';
    const DATA_MAX_ERROR    = 'Error for value "%s" for "%s": MAX_VALUE=';

    protected static $defaults = [
        'format'    => 'Y-m-d H:i:s',
        'default'   => 'now',
        'min'       => null,
        'max'       => null
    ];

    protected static $validDateOptions = [
        'now',
        'tomorrow',
        'next week',
        'next month',
        'next year',
        'yesterday',
        'previous week',
        'previous month',
        'previous year',
    ];

    /**
     * Date constructor.
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
        $this->isString();
        $this->checkFormat();
        $this->checkMin();
        $this->checkMax();
        return true;
    }
    private function isString()
    {
        if (!is_string($this->datum)) {
            $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
            $this->throwException();
        }
    }

    private function checkFormat()
    {

        if (in_array($this->datum, self::$validDateOptions, true)) {
            return true;
        }
        try {
            Assertion::date($this->datum, $this->options['format']);
        } catch (BadMethodCallException $e) {
            $this->errorMessageTemplate = self::DATA_FORMAT_ERROR;
            $this->throwException();
        }
    }

    private function checkMin()
    {
        if ($this->options['min'] === null) {
            return true;
        }
        if ($this->datum < $this->options['min']) {
            $this->errorMessageTemplate = self::DATA_MIN_ERROR . $this->options['min'];
            $this->throwException();
        }
        return true;
    }

    private function checkMax()
    {
        if ($this->options['max'] === null) {
            return true;
        }
        if ($this->datum > $this->options['min']) {
            $this->errorMessageTemplate = self::DATA_MIN_ERROR . $this->options['max'];
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
            $date = new DateTime($this->datum);
        } catch (InvalidArgumentException $e) {
            $date = new DateTime($this->options['default']);
        }
        return $date->format($this->options['format']);
    }
}
