<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Text implements DataTypeInterface
{
    use DataTypeFilterTrait;
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
        if (!is_string($this->datum)) {
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
        if (mb_strlen($this->datum) < (int) $this->options['min']) {
            $this->errorMessageTemplate = self::DATA_LENGTH . 'MIN:' . $this->options['min'];
            $this->throwException();
        }
    }

    private function checkLengthBetween()
    {
        $stringLength = mb_strlen($this->datum);
        if ($stringLength < (int) $this->options['min'] || $stringLength > (int) $this->options['max']) {
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
            return filter_var(
                mb_substr($this->datum, 0, $length),
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
        }
        if (strlen($this->datum) < (int) $this->options['min']) {
            $padType = self::$padOptions[$this->options['pad']] ?: STR_PAD_RIGHT;
            $padding = $this->options[$this->options['pad'].'_pad'];
            return filter_var(
                str_pad($this->datum, (int) $this->options['min'], $padding, $padType),
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
        }
        return filter_var($this->datum, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    }
}
