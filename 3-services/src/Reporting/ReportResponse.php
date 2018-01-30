<?php

namespace App\Reporting;

class ReportResponse
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFilename(): string
    {
        return basename($this->path);
    }
}
