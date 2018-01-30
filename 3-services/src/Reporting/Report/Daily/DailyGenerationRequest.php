<?php

namespace App\Reporting\Report\Daily;

use App\Reporting\Command\GenerationRequest;

class DailyGenerationRequest implements GenerationRequest
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
