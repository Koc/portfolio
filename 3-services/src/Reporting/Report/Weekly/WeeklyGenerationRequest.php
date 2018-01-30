<?php

namespace App\Reporting\Report\Weekly;

use App\Reporting\Command\GenerationRequest;

class WeeklyGenerationRequest implements GenerationRequest
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
