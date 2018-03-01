<?php

namespace App\Reporting;

use App\Reporting\Command\GenerationRequest;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GenerationRequestFactory
{
    private $serializer;

    public function __construct(DenormalizerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getGenerationRequest(string $generationRequestClass, array $data): GenerationRequest
    {
        $generationRequest = $this->serializer->denormalize($data, $generationRequestClass);

        if (!$generationRequest instanceof GenerationRequest) {
            throw new \BadMethodCallException(
                sprintf('Class "%s" must implements "%s" interface.', $generationRequestClass, GenerationRequest::class)
            );
        }

        return $generationRequest;
    }
}
