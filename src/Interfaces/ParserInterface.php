<?php
declare(strict_types=1);

namespace Selami\Entity\Interfaces;


use UnexpectedValueException;

interface ParserInterface
{
    /**
     * @return array
     * @throws UnexpectedValueException
     */
    public function parse();

    /**
     * @return bool
     */
    public function checkFormat();
}