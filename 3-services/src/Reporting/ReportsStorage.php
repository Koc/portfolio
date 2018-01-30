<?php

namespace App\Reporting;

class ReportsStorage
{
    private const SUBDIR = 'reports';

    private $uploadDir;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir.'/'.self::SUBDIR;
    }

    public function generateTargetPath(string $format): string
    {
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir) && !is_dir($this->uploadDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->uploadDir));
            }
        }

        return sprintf('%s/%s.%s', $this->uploadDir, time(), $format);
    }

    public function getTargetPath(string $filename): string
    {
        return $this->uploadDir.'/'.$filename;
    }
}
