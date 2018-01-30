<?php

namespace App\Controller\Group;

use App\Reporting\ReportsStorage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadReportsAction
{
    private $storage;

    public function __construct(ReportsStorage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke($filename)
    {
        $targetPath = $this->storage->getTargetPath($filename);

        if (!is_file($targetPath)) {
            throw new NotFoundHttpException(sprintf('File "%s" not found.', $filename));
        }

        $response = new BinaryFileResponse($targetPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->deleteFileAfterSend(true);

        return $response;
    }
}
