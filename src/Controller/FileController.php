<?php

namespace App\Controller;

use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/file/images/{filename}', name: 'file_image_download', methods: ['GET'])]
    public function downloadImage(FileService $fileService, string $filename): Response
    {
        $publicDir = $this->getParameter('kernel.project_dir').'/public/';
        $file = $fileService->download(FileService::FILE_TYPE_IMAGE, $filename, $publicDir);

        $response = new Response();
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->setContent($file->getContent());

        return $response;
    }
}
