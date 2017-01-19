<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use Assert\Assertion;
use Assert\AssertionFailedException;

class Text extends DataTypeAbstract implements DataTypeInterface
{
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';
    const DATA_LENGTH       = 'Assertion failed for value "%s" for "%s" : INVALID_TEXT_LENGTH';

    protected static $defaults = [
        'default'   => '',
        'min'       => 0,
        'max'       => null,
        'pad'       => 'left',
        'left_pad'  => ' ',
        'right_pad' => ' '
    ];

    protected static $padOptions = [
        'left'  => STR_PAD_LEFT,
        'right' => STR_PAD_RIGHT
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
        $this->checkLength();
        return true;
    }

    private function isString()
    {
        try {
            Assertion::string($this->datum);
        } catch (AssertionFailedException $e) {
            $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
            $this->throwException();
        }
        return true;
    }

    private function checkLength()
    {
        if ($this->options['max'] !== null) {
            return $this->checkLengthBetween();
        }
        try {
            Assertion::minLength($this->datum, (int) $this->options['min']);
        } catch (AssertionFailedException $e) {
            $this->errorMessageTemplate = self::DATA_LENGTH . 'MIN:' . $this->options['min'];
            $this->throwException();
        }
    }
    private function checkLengthBetween()
    {
        try {
            Assertion::betweenLength($this->datum, (int) $this->options['min'], (int) $this->options['max']);
        } catch (AssertionFailedException $e) {
            $this->errorMessageTemplate = self::DATA_LENGTH
                . 'MIN:' . $this->options['min']
                . 'MAX:' . $this->options['max'];
            $this->throwException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function normalize()
    {
        if (null === $this->datum) {
            return $this->options['default'];
        }
        if ($this->options['max'] !== null) {
            $length = (int) $this->options['max'];
            return mb_substr($this->datum, 0, $length);
        }
        if (strlen($this->datum) < (int) $this->options['min']) {
            $padType = self::$padOptions[$this->options['pad']] ?: STR_PAD_RIGHT;
            $padding = $this->options[$this->options['pad'].'_pad'];
            return str_pad($this->datum, (int) $this->options['min'], $padding, $padType);
        }
        return $this->datum;
    }
}
