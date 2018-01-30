<?php

namespace App\Controller\WMAPK;

use App\Entity\WMAPK\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UploadAction
{
    public function __invoke(Request $request)
    {
        $request->setRequestFormat('json');
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('No file present in the request');
        }

        $file = new File();
        $file->setUploadedFile($uploadedFile);

        return $file;
    }
}
