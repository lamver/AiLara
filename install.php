<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit','512M');
error_reporting(E_ALL);

class Installer {

    const URL_REPOSITORY = 'https://github.com/lamver/AiLara/archive/refs/heads/main.zip';
    const ARCHIVE_FILE_ZIP_NAME = 'main.zip';

    const APP_PATH_TO_UPDATE_ARCHIVE = 'update/main.zip';
    const APP_PATH_TO_UPDATE_ARCHIVE_EXTRACT_FILES = 'update/extract_files';

    static public function downloadArchiveRepository()
    {
        $url = self::URL_REPOSITORY;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $download = curl_exec($ch);
        curl_close($ch);

        try {
            file_put_contents(self::ARCHIVE_FILE_ZIP_NAME, file_get_contents(self::URL_REPOSITORY));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    static public function extractArchiveRepository()
    {
        $zipfile = self::ARCHIVE_FILE_ZIP_NAME;

        // Название папки для распаковки
        $extractPath = getcwd().'/storage/app/update/extract_files';

        $extractPath = str_replace('public/' , '', $extractPath);


        echo $extractPath . '<br>';

        if (!is_dir($extractPath)) {
            echo 'gfff <br>';
            mkdir($extractPath, 0755, true);
        }

        $zipfile = getcwd() . '/'.self::ARCHIVE_FILE_ZIP_NAME;

        return self::unzipFile($zipfile, $extractPath);
    }

    static public function unzipFile($file_path, $dest) {
        echo $file_path . '<br>';
        echo $dest . '<br>';
        $zip = new ZipArchive;

        if(!is_dir($dest) ) {
            return 'Нет папки, куда распаковывать...';
        }

        // открываем архив
        if(true === $zip->open($file_path) ) {
            $zip->extractTo( $dest );
            $zip->close();
            return true;
        } else {
            return 'Произошла ошибка при распаковке архива';
        }
    }

    static public function moveFiles($sourceDir, $destDir) {
        if (!is_dir($sourceDir) || !is_dir($destDir)) {
            return false;
        }

        $files = glob($sourceDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                $fileName = basename($file);
                $destFile = $destDir . '/' . $fileName;
                if (!rename($file, $destFile)) {
                    return false;
                }
            } elseif (is_dir($file)) {
                $dirName = basename($file);
                $newDestDir = $destDir . '/' . $dirName;
                if (!is_dir($newDestDir)) {
                    mkdir($newDestDir);
                }
                if (!self::moveFiles($file, $newDestDir)) {
                    return false;
                }
            }
        }

        return true;
    }

    static public function composerInstall()
    {
        $destDir = getcwd();
        $destDir = str_replace('public' , '', $destDir);
        chdir($destDir);
        echo $destDir;
        system('php composer.phar install');
        $composerBinPath = shell_exec('which composer');
        echo $composerBinPath;

        exec('cd ../ && /opt/cpanel/composer/bin/composer install', $output, $result_code);
        echo "<pre>";
        print_r($output);
        print_r($result_code);
        echo "</pre>";

        /*echo exec('composer install', $output, $return);
        $composer = file_get_contents('https://getcomposer.org/installer');*/

/*        file_put_contents('../composer.phar', $composer);
        echo shell_exec('cd ..; chmod +x composer.phar;');
        echo '<br>';
        echo shell_exec('cd ..; ./composer.phar install --prefer-source --no-interaction;');
        echo '<br>';
        echo shell_exec('cd ..; ./composer.phar -V;');
        echo '<br>';*/
/*        echo '<br>Current dir:<br>';
        $destDir = getcwd();
        $destDir = str_replace('public' , '', $destDir);
        echo $destDir;
        echo '<br>';

        chdir($destDir);

        echo '<br>Current dir:<br>';
        $destDir = getcwd();
        echo $destDir;
        echo '<br>';*/

        //echo "composer: " . shell_exec('cd ..; composer install --no-interaction --no-dev --prefer-dist 2>&1');
    }

    static public function getHomeDir(): string
    {
        return dirname(getcwd(), 2);
    }
}
    echo 'start' . '<br>';
    if (Installer::downloadArchiveRepository()) {
    echo 'download archive successfully'. '<br>';
    if (!Installer::extractArchiveRepository()) {
        die('Ошибка распаковки архива');
    }

    $sourceDir = getcwd().'/storage/app/update/extract_files/AiLara-main';
    $sourceDir = str_replace('public/' , '', $sourceDir);
    $destDir = getcwd();
    $destDir = str_replace('public' , '', $destDir);

    Installer::moveFiles($sourceDir, $destDir);
    Installer::composerInstall();
}

