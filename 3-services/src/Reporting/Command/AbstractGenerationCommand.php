<?php

namespace App\Reporting\Command;

use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractGenerationCommand
{
    /**
     * @Groups({"reporting"})
     *
     * @var string
     */
    private $format;

    /**
     * @Groups({"reporting"})
     *
     * @var array
     */
    private $generationRequests = [];

    /**
     * @return array
     */
    public function getGenerationRequests(): array
    {
        return $this->generationRequests;
    }

    public function getGenerationRequest(string $generatorClass): array
    {
        if (!isset($this->generationRequests[$generatorClass])) {
            throw new \InvalidArgumentException(
                sprintf('No generation request present for generator class "%s".', $generatorClass)
            );
        }

        return $this->generationRequests[$generatorClass];
    }

    public function setGenerationRequests(array $generationRequests): void
    {
        $this->generationRequests = $generationRequests;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}
