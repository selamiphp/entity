<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use InvalidArgumentException;

trait DataTypeFilterTrait
{
    protected $filterFlags;
    protected $sanitizeFlags;
    /**
     * DataTypeFilterAbstract constructor.
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
        if (!filter_var($this->datum, $this->filterFlags)) {
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
