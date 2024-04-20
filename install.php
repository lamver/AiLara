<?php

use Illuminate\Support\Facades\Artisan;

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

    /**
     * @return bool
     */
    static public function extractArchiveRepository(): bool
    {
        // Название папки для распаковки
        $extractPath = getcwd().'/storage/app/update/extract_files';

        $extractPath = str_replace('public/' , '', $extractPath);

        echo $extractPath . '<br>';

        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zipfile = getcwd() . '/'.self::ARCHIVE_FILE_ZIP_NAME;

        $resultExtract = self::unzipFile($zipfile, $extractPath);

        unlink($zipfile);

        return $resultExtract;
    }

    /**
     * @param $file_path
     * @param $dest
     * @return bool
     */
    static public function unzipFile($file_path, $dest): bool
    {
        echo $file_path . '<br>';
        echo $dest . '<br>';
        $zip = new ZipArchive;

        if(!is_dir($dest) ) {
            echo 'Нет папки, куда распаковывать...' . '<br>';
            return false;
        }

        // открываем архив
        if(true === $zip->open($file_path) ) {
            $zip->extractTo( $dest );
            $zip->close();
            return true;
        } else {
            echo 'Произошла ошибка при распаковке архива' . '<br>';
            return false;
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
        // Название папки для распаковки
        $extractPath = getcwd();

        $extractPath = str_replace('public' , '', $extractPath);

        echo 'getcwd: ' . getcwd() . '<br>';
        echo $extractPath . '<br>';

        $zipfile = $extractPath . '/vendor.zip';

        return self::unzipFile($zipfile, $extractPath);
    }

    static public function getHomeDir(): string
    {
        return dirname(getcwd(), 2);
    }

    /**
     * @param $data
     * @return bool
     */
    static public function createEnv($data = [])
    {
        $env = [
            'APP_NAME' => 'AiLara',
            'APP_ENV' => 'local',
            'APP_KEY' => '',
            'APP_DEBUG' => 'true',
            'APP_URL' => '',
            'FRONTEND_URL' => '',
            'LOG_LEVEL' => 'debug',
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '3306',
            'DB_DATABASE' => 'laravel',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => 'password',
            'BROADCAST_DRIVER' => 'log',
            'CACHE_DRIVER' => 'file',
            'FILESYSTEM_DISK' => 'local',
            'QUEUE_CONNECTION' => 'sync',
            'SESSION_DRIVER' => 'file',
            'SESSION_LIFETIME' => '120',
            'MEMCACHED_HOST' => '127.0.0.1',
            'REDIS_HOST' => '127.0.0.1',
            'REDIS_PASSWORD' => 'null',
            'REDIS_PORT' => '6379',
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => 'mailpit',
            'MAIL_PORT' => '1025',
            'MAIL_USERNAME' => 'null',
            'MAIL_PASSWORD' => 'null',
            'MAIL_ENCRYPTION' => 'null',
            'MAIL_FROM_ADDRESS' => 'hello@example.com',
            'MAIL_FROM_NAME' => '${APP_NAME}',
            'AWS_ACCESS_KEY_ID' => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_DEFAULT_REGION' => 'us-east-1',
            'AWS_BUCKET' => '',
            'AWS_USE_PATH_STYLE_ENDPOINT' => 'false',
            'PUSHER_APP_ID' => '',
            'PUSHER_APP_KEY' => '',
            'PUSHER_APP_SECRET' => '',
            'PUSHER_HOST' => '',
            'PUSHER_PORT' => '443',
            'PUSHER_SCHEME' => 'https',
            'PUSHER_APP_CLUSTER' => 'mt1',
            'VITE_APP_NAME' => '${APP_NAME}',
            'VITE_PUSHER_APP_KEY' => '${PUSHER_APP_KEY}',
            'VITE_PUSHER_HOST' => '${PUSHER_HOST}',
            'VITE_PUSHER_PORT' => '${PUSHER_PORT}',
            'VITE_PUSHER_SCHEME' => '${PUSHER_SCHEME}',
            'VITE_PUSHER_APP_CLUSTER' => '${PUSHER_APP_CLUSTER}',
        ];

        $anvFile = '';

        $data['APP_KEY'] = self::generateRandomString(32);
        $data['APP_URL'] = self::getProtocolHostPort();
        $data['FRONTEND_URL'] = self::getProtocolHostPort();

        foreach ($env as $envKey => $envValue) {
            if (isset($data[$envKey])) {
                $anvFile .= $envKey.'='.$data[$envKey] . PHP_EOL;
                continue;
            }
            $anvFile .= $envKey.'='.$envValue . PHP_EOL;
        }

        $destDir = getcwd();
        $destDir = str_replace('public' , '', $destDir);

        if (!file_put_contents($destDir . '/.env', $anvFile)) {
            return false;
        }

        return true;
    }

    /**
     * @param $servername
     * @param $port
     * @param $dbname
     * @param $username
     * @param $password
     * @return bool
     */
    static public function checkDbConnection($servername, $port, $dbname, $username, $password): bool
    {
        try {
            $conn = new mysqli($servername, $username, $password, $dbname, $port);
        } catch (Exception $e) {
            echo $e->getMessage() . '<br>';
            return false;
        }

        if ($conn->connect_error) {
            return false;
        } else {
            $conn->close();
            return true;
        }
    }

    /**
     * @param $length
     * @return string
     */
    public static function generateRandomString($length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * @return string
     */
    static public function getProtocolHostPort(): string
    {
        $host = $_SERVER['HTTP_HOST'];
        $protocol = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $port = $_SERVER['SERVER_PORT'];

        return $protocol . '://' . $host . ($port != ':80' && $port != '443' ? ':'.$port.'/' : '/');
    }
}

if (isset($_GET['step'])) {
    session_start();
    if ($_GET['step'] == 0) {
        header("Refresh:2; url=/install.php?step=1");
        echo 'step #0' . '<br>';
        exit;
    }

    if ($_GET['step'] == 1) {
        echo 'step #1' . '<br>';
        echo 'Download archive' . '<br>';
        if (Installer::downloadArchiveRepository()) {
            header("Refresh:2; url=/install.php?step=2");
            exit;
        }
        echo 'Download archive error!' . '<br>';
    }

    if ($_GET['step'] == 2) {
        echo 'step #2' . '<br>';
        echo 'Extract archive' . '<br>';
        if (Installer::extractArchiveRepository()) {
            header("Refresh:2; url=/install.php?step=3");
            exit;
        }

        echo 'Extract archive error!' . '<br>';
    }

    if ($_GET['step'] == 3) {
        echo 'step #3' . '<br>';
        echo 'Move files' . '<br>';
        $sourceDir = getcwd().'/storage/app/update/extract_files/AiLara-main';
        $sourceDir = str_replace('public/' , '', $sourceDir);
        $destDir = getcwd();
        $destDir = str_replace('public' , '', $destDir);

        if (Installer::moveFiles($sourceDir, $destDir)) {
            header("Refresh:2; url=/install.php?step=4");
            exit;
        }

        echo 'Move files error!' . '<br>';
    }

    if ($_GET['step'] == 4) {
        echo 'step #4' . '<br>';
        echo 'Install vendor files' . '<br>';

        if (Installer::composerInstall()) {
            header("Refresh:2; url=/install.php?step=5");
            exit;
        }

        echo 'Move files error!' . '<br>';
    }

    if ($_GET['step'] == 5) {
        echo 'step #4' . '<br>';
        echo 'Set DB connections' . '<br>';

        if (
            isset($_POST)
            && isset($_POST['DB_HOST'])
            && isset($_POST['DB_PORT'])
            && isset($_POST['DB_DATABASE'])
            && isset($_POST['DB_USERNAME'])
            && isset($_POST['DB_PASSWORD'])
        ) {
            if (Installer::checkDbConnection($_POST['DB_HOST'], $_POST['DB_PORT'], $_POST['DB_DATABASE'], $_POST['DB_USERNAME'], $_POST['DB_PASSWORD'])) {
                Installer::createEnv($_POST);
                echo 'DB connections success' . '<br>';

                $_SESSION['route_install'] = Installer::generateRandomString(10);
                echo 'Sess install: ' . $_SESSION['route_install'] . '<br>';
                header("Refresh:2; url=/install_" . $_SESSION['route_install']);
                exit;
            } else {
                echo 'Error DB connections' . '<br>';
            }
        }

        echo '<form method="post">';
        echo 'DB_HOST' . '<br>';
        echo '<input name="DB_HOST" value="127.0.0.1">' . '<br>';
        echo 'DB_PORT' . '<br>';
        echo '<input name="DB_PORT" value="3306">' . '<br>';
        echo 'DB_DATABASE' . '<br>';
        echo '<input name="DB_DATABASE" value="">' . '<br>';
        echo 'DB_USERNAME' . '<br>';
        echo '<input name="DB_USERNAME" value="">' . '<br>';
        echo 'DB_PASSWORD' . '<br>';
        echo '<input name="DB_PASSWORD" value="">' . '<br>';
        echo '<button type="submit">Apply</button>' . '<br>';
        echo '</form>';

/*        if (Installer::composerInstall()) {
            header("Refresh:2; url=/install.php?step=5");
            exit;
        }*/

        //echo 'Move files error!' . '<br>';
    }

    if ($_GET['step'] == 20) {
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
    }

    die();
}

?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        iframe {
            width: 100%;
            max-width: 1200px;
            height: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            border: none;
        }
    </style>
</head>
<body>

<iframe src="?step=0"></iframe>
<script type="application/javascript"> тут содержимое </script>
</body>
</html>


