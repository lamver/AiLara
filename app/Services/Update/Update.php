<?php

namespace App\Services\Update;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Update
{
    const URL_REPOSITORY = 'https://github.com/lamver/AiLara/archive/refs/heads/main.zip';

    const APP_PATH_TO_UPDATE_ARCHIVE = 'update/main.zip';
    const APP_PATH_TO_UPDATE_ARCHIVE_EXTRACT_FILES = 'update/extract_files';

    static public function downloadArchiveRepository()
    {
        if (stripos(url()->current(), 'localhost')) {
            return true;
        }

        $r = file_get_contents(self::URL_REPOSITORY);


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

    /**
     * @param $fileCandidate
     * @return bool|string
     */
    static public function updateFile($fileCandidate)
    {
        $directoryPath = pathinfo($fileCandidate['pathWithoutDirExtract'])['dirname'];
        Log::channel('update')->log('debug', '$directoryPath: ' . $directoryPath);

        Log::channel('update')->log('debug', $fileCandidate['pathWithoutDirExtract']);
        try {
            if (file_exists($fileCandidate['pathWithoutDirExtract'])) {
                chmod($fileCandidate['pathWithoutDirExtract'], 0744);
            } else {

                $directoryPath = pathinfo($fileCandidate['pathWithoutDirExtract'])['dirname'];
                Log::channel('update')->log('debug', '$directoryPath: ' . $directoryPath);
                if (!is_dir($directoryPath)) {
                    try {
                        mkdir($directoryPath, 0755, true);
                    } catch (\Exception $e) {
                        Log::channel('update')->log('debug', 'mkdir_message: ' . $e->getMessage());
                    }
                }

                file_put_contents($fileCandidate['pathWithoutDirExtract'], '');
                chmod($fileCandidate['pathWithoutDirExtract'], 0744);
            }



            if (stripos(url()->current(), 'localhost')) {
                return true;
            } else {
                return copy($fileCandidate['path'], $fileCandidate['pathWithoutDirExtract']);
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @return void
     */
    static public function composerUpdate(): void
    {
        self::vendorInstall();
        //chdir(base_path());
        //exec('composer install', $output, $return);
        // Получаем вывод работы команды
        // Выводим результат
        //return $output;
    }

    /**
     * @return void
     */
    static public function vendorInstall(): void
    {
        $zipFile = base_path() . '/storage/app/update/extract_files/AiLara-main/vendor.zip';
        $extractLocation = base_path() . '/vendor_up';

        $zip = new \ZipArchive;

        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractLocation);
            $zip->close();
            //echo 'Zip archive extracted successfully.';

            /*if (file_exists(base_path() . '/vendor')) {
                self::renameRecursive(base_path() . '/vendor', base_path() . '/vendor_down');
            }

            self::renameRecursive(base_path() . '/vendor_up', base_path() . '/vendor');

            if (file_exists(base_path() . '/vendor_down')) {
                self::deleteDirectory(base_path() . '/vendor_down');
            }*/
        } else {
            //echo 'Failed to extract the zip archive.';
        }
    }

    /**
     * @param $dir
     * @return bool
     */
    static public function deleteDirectory($dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * @param $old_dir
     * @param $new_dir
     * @return void
     */
    static function renameRecursive($old_dir, $new_dir): void
    {
        $dir = opendir($old_dir);
        @mkdir($new_dir);

        while ($file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($old_dir . '/' . $file)) {
                    self::renameRecursive($old_dir . '/' . $file, $new_dir . '/' . $file);
                } else {
                    rename($old_dir . '/' . $file, $new_dir . '/' . $file);
                }
            }
        }

        closedir($dir);
        rmdir($old_dir);
    }

    /**
     * @return string
     */
    static public function migrate()
    {
        chdir(base_path());
        Artisan::call('migrate');
        return Artisan::output();
    }

    /**
     * @return string
     */
    static public function optimize(): string
    {
        chdir(base_path());

        $output = '';

        Artisan::call('cache:clear');
        $output .= Artisan::output();

        Artisan::call('config:cache');
        $output .= Artisan::output();

        //Artisan::call('config:cache');
        //$output .= "\n" . Artisan::output();

        //Artisan::call('route:clear');
        //$output .= "\n" . Artisan::output();

        //Artisan::call('route:cache');
        //$output .= "\n" . Artisan::output();

        return $output;
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

                if (stripos($pathWithoutDirExtract, '/public/robots.txt')) {
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
