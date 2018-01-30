<?php

namespace App\Reporting\Report\Basic;

use App\Reporting\Command\GenerationRequest;

class BasicGenerationRequest implements GenerationRequest
{
    private $dateFrom;

    private $dateTo;

    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTime $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(\DateTime $dateTo): void
    {
        $this->dateTo = $dateTo;
    }
}
