<?php
declare(strict_types=1);

namespace Selami\Entity\Interfaces;

use InvalidArgumentException;

interface DataTypeInterface
{
    /**
     * @throws InvalidArgumentException
     * @return true
     */
    public function assert();

    /**
     * @return mixed
     */
    public function normalize();
}
