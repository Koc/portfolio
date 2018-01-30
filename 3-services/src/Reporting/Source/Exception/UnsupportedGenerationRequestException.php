<?php

namespace App\Reporting\Source\Exception;

class UnsupportedGenerationRequestException extends \InvalidArgumentException
{
    public function __construct(string $expectedType, $objectGiven)
    {
        parent::__construct(
            sprintf(
                'Expected object of type: "%s", "%s" given.',
                $expectedType,
                is_object($objectGiven) ? get_class($objectGiven) : gettype($objectGiven)
            )
        );
    }
}
