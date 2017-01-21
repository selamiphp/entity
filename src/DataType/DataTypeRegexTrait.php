<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

trait DataTypeRegexTrait
{
    use DataTypeFilterTrait;

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        if (@!preg_match(
            $this->regex,
            $this->datum
        )
        ) {
            $error = error_get_last();
            $this->errorMessageTemplate = ($error !== null) ? $error['message'] : self::DATA_FORMAT_ERROR;
            $this->throwException();
        }
        return true;
    }
}
