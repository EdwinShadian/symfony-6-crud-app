<?php

namespace App\Service;

use App\Exception\FileTypeException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileService
{
    public const FILE_TYPE_IMAGE = 'image';

    private const FILE_MIME_TYPE_TO_DIR_MAP = [
        'image/png' => 'file/images',
        'image/jpeg' => 'file/images',
    ];

    private const FILE_TYPE_TO_DIR_MAP = [
        'image' => 'file/images',
    ];

    public function __construct(
        private readonly Client $client = new Client(),
    ) {
    }

    /**
     * @throws FileTypeException
     */
    public function upload(File $file, string $publicDir): string
    {
        $fileDir = self::FILE_MIME_TYPE_TO_DIR_MAP[$file->getMimeType()] ?? null;

        if (null === $fileDir) {
            throw new FileTypeException();
        }

        $dir = $publicDir.$fileDir;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileName = 'image-'.hash('sha256', $file->getContent()).'.'.$file->guessExtension();

        $file->move($dir, $fileName);

        return $fileDir.'/'.$fileName;
    }

    public function download(string $fileType, string $filename, string $publicDir): File
    {
        $filePath = $publicDir.self::FILE_TYPE_TO_DIR_MAP[$fileType].'/'.$filename;

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException();
        }

        return new File($filePath);
    }

    /**
     * @throws GuzzleException
     */
    public function saveImageFromUrl(string $url, string $publicDir): string
    {
        $response = $this->client->get($url);
        $imageContent = $response->getBody()->getContents();

        $fileDir = $publicDir.self::FILE_TYPE_TO_DIR_MAP[self::FILE_TYPE_IMAGE];
        $fileName = 'image-'.hash('sha256', $imageContent).'.jpg';

        $savePath = $fileDir.'/'.$fileName;

        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0755, true);
        }

        file_put_contents($savePath, $imageContent);

        return self::FILE_TYPE_TO_DIR_MAP[self::FILE_TYPE_IMAGE].'/'.$fileName;
    }
}
