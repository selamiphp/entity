<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use InvalidArgumentException;

class Regex implements DataTypeInterface
{
    use DataTypeRegexTrait;

    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_FORMAT';

    protected $regex;
    protected $sanitizeFlags;
    protected static $defaults = [
        'default'       => null,
        'regex'         => null,
        'sanitize_flag' => null
    ];

    /**
     * Regex constructor.
     * @param string $key
     * @param mixed $datum
     * @param array $options
     * @throws InvalidArgumentException
     */
    public function __construct(string $key, $datum, array $options = [])
    {
        if (!isset($options['regex']) || empty($options['regex'])) {
            throw new InvalidArgumentException('$options[regex] can\'t be empty and must be a valid regex');
        }
        $this->key = $key;
        $this->datum = $datum;
        $this->regex = $options['regex'];
        $this->sanitizeFlags = $options['sanitize_flag'] ?? null;
        $this->checkValidOptions($options);
        $this->options = array_merge(self::$defaults, $options);
    }
}
