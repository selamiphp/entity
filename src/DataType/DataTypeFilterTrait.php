<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use InvalidArgumentException;

trait DataTypeFilterTrait
{
    protected $invalidOptionsErrorMessage = 'Option: %s is invalid. You should remove it or check for typo.';
    /**
     * @var string
     */
    protected $key;
    /**
     * @var mixed
     */
    protected $datum;
    /**
     * @var array
     */
    protected $options = [];

    protected $errorMessageTemplate;
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
        $filter = array_shift($this->filterFlags);
        $options =['flags' => $this->filterFlags[0]??null];
        if (!filter_var($this->datum, $filter, $options)) {
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



    protected function checkValidOptions(array $options)
    {
        $validOptions = array_keys($this::$defaults);
        foreach ($options as $optionKey => $optionValue) {
            if (!in_array($optionKey, $validOptions, true)) {
                throw new InvalidArgumentException(sprintf(self::INVALID_OPTIONS, $optionKey));
            }
        }
    }

    protected function throwException()
    {
        if (getType($this->datum) === 'array') {
            $this->datum = '{NoOtConvertibleToStringValue}';
        }
        $message = sprintf(
            $this->errorMessageTemplate,
            $this->datum,
            $this->key
        );
        throw new InvalidArgumentException($message);
    }
}
