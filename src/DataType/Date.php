<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use DateTime;
use InvalidArgumentException;

class Date implements DataTypeInterface
{
    use DataTypeFilterTrait;
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
        $dateTime = DateTime::createFromFormat($this->options['format'], $this->datum);
        if (false === $dateTime || $this->datum !== $dateTime->format($this->options['format'])) {
            $this->errorMessageTemplate = self::DATA_FORMAT_ERROR;
            $this->throwException();
        }
    }

    private function checkMin()
    {
        if (($this->options['min'] !== null) && $this->datum < $this->options['min']) {
            $this->errorMessageTemplate = self::DATA_MIN_ERROR . ' ' . $this->options['min'];
            $this->throwException();
        }
    }

    private function checkMax()
    {
        if (($this->options['max'] !== null) && ($this->datum > $this->options['max'])) {
            $this->errorMessageTemplate = self::DATA_MAX_ERROR . ' ' . $this->options['max'];
            $this->throwException();
        }
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
