<?php

namespace AppBundle\Model\ResponseDTO;

use JMS\Serializer\Annotation as Serializer;

class StatusResponse
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    /**
     * Status text
     *
     * @Serializer\Type("string")
     * @var string
     */
    public $status;

    /**
     * Status message
     *
     * @Serializer\Type("string")
     * @var string
     */
    public $message;

    /**
     * StatusResponse constructor.
     * @param string $status
     * @param string $message
     */
    public function __construct(string $message = '', string $status = self::STATUS_SUCCESS)
    {
        $this->status = $status;
        $this->message = $message;
    }
}
