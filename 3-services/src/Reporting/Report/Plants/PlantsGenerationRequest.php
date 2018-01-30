<?php

namespace App\Reporting\Report\Plants;

use App\Reporting\Command\GenerationRequest;

class PlantsGenerationRequest implements GenerationRequest
{
    private $date;

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }
}
