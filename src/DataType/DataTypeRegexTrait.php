<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use InvalidArgumentException;
use Selami\Entity\Interfaces\DataTypeInterface;

trait DataTypeRegexTrait
{
    protected $sanitizeFlags;
    /**
     * DataTypeRegexAbstract constructor.
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
            $this->regex,
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
            return $this->sanitize();
        } catch (InvalidArgumentException $e) {
            return $this->options['default'];
        }
    }

    protected function sanitize()
    {
        if ($this->sanitizeFlags !== null) {
            return filter_var($this->datum, $this->sanitizeFlags);
        }
        return $this->datum;
    }
}
