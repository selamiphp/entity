<?php
declare(strict_types=1);

namespace Selami\Entity;

/**
 * A Note:
 * Since the built-in php function gettype returns "double" variabe type, here is the workaround function
 * See http://php . net/manual/en/function . gettype . php => Possible values for the returned string are:
 * "double" (for historical reasons "double" is returned in case of a float, and not simply "float")
 *
 * @param mixed     $value
 * @return string
 */
function getType($value)
{
    return [
        'boolean'   => 'boolean',
        'string'    => 'string',
        'integer'   => 'integer',
        'long'      => 'integer',
        'double'    => 'float',
        'float'     => 'float',
        'array'     => 'array',
        'null'      => 'null'
    ][strtolower(gettype($value))];
}
