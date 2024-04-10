<?php

namespace App\Services\Update;

use Illuminate\Support\Facades\Storage;

class Update
{
    const URL_REPOSITORY = 'https://github.com/lamver/AiLara/archive/refs/heads/main.zip';

    const APP_PATH_TO_UPDATE_ARCHIVE = 'update/main.zip';
    const APP_PATH_TO_UPDATE_ARCHIVE_EXTRACT_FILES = 'update/extract_files';

    static public function downloadArchiveRepository()
    {
        $r = file_get_contents('https://github.com/lamver/AiLara/archive/refs/heads/main.zip');

        return Storage::put('update/main.zip', $r);
    }

    static public function extractArchiveRepository()
    {
        $zip = new \ZipArchive;
        $zip_path = storage_path('app/'.self::APP_PATH_TO_UPDATE_ARCHIVE);
        $extract_path = storage_path('app/'.self::APP_PATH_TO_UPDATE_ARCHIVE_EXTRACT_FILES);

        if ($zip->open($zip_path) === TRUE) {
            $zip->extractTo($extract_path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    static public function updateFile($fileCandidate)
    {

        try {
            chmod($fileCandidate['pathWithoutDirExtract'], 0744);

            if (stripos(url()->current(), 'localhost')) {
                return true;
            } else {
                return copy($fileCandidate['path'], $fileCandidate['pathWithoutDirExtract']);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    static public function getFileSize($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);

        return $size;
    }

    public static function getFileToCandidateUpdate($dir = null, &$fc = []) {

        $pathToExtractFiles = storage_path('app/'.Update::APP_PATH_TO_UPDATE_ARCHIVE_EXTRACT_FILES.'/AiLara-main');

        if (is_null($dir)) {
            $dir = $pathToExtractFiles;
        }

        $files = scandir($dir);

        foreach($files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $dir.'/'.$file;

                $pathWithoutDirExtract = str_replace($pathToExtractFiles, '', $path);

                if (stripos($pathWithoutDirExtract, '/vendor/')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '/.git/')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '/.idea/')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '/public/')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '/storage/')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '/.gitattributes')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '/.env')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '.htaccess')) {
                    continue;
                }

                if (stripos($pathWithoutDirExtract, '.editorconfig')) {
                    continue;
                }

                if (is_dir($path)) {
                    self::getFileToCandidateUpdate($path, $fc);
                } else {
                    $fc[] = [
                        'path' => $path,
                        'pathWithoutDirExtract' => base_path().$pathWithoutDirExtract,
                        'fileSize' => filesize($path),
                        'filemtime' => filemtime($path),
                    ];
                }
            }
        }

        return $fc;
    }
}