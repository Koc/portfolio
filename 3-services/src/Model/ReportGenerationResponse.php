<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class ReportGenerationResponse
{
    /**
     * @Groups({"reporting"})
     */
    public $reportFilename;

    /**
     * @Groups({"reporting"})
     */
    public $reportUrl;

    public function __construct(string $reportFilename, string $reportUrl)
    {
        $this->reportFilename = $reportFilename;
        $this->reportUrl = $reportUrl;
    }
}
