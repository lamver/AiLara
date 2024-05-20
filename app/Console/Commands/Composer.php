<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Composer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:composer {comma=install} {packet?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $comma = $this->argument('comma');

        if ($comma == 'install') {
            exec('composer install');
        }

        if ($comma == 'require') {
            exec('composer require ' . $this->argument('packet'));
        }

        $this->output = self::createVendorZip();

        //echo PHP_EOL;
    }

    /**
     * @return void
     */
    static public function createVendorZip(): string
    {

        // Создаем объект ZipArchive
        $zip = new \ZipArchive();

        // Название архива
        $zipName = base_path().'/vendor.zip';

        if (file_exists($zipName)) {
            unlink($zipName);
        }

        // Путь к каталогу, который нужно заархивировать
        $source = base_path().'/vendor';

        // Создание и открытие архива
        if ($zip->open($zipName, \ZipArchive::CREATE) === TRUE) {
            // Рекурсивный обход директории и добавление файлов
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Пропускаем недопустимые файлы
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($source) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            // Закрываем архив
            $zip->close();

            return 'Архив создан успешно!';
        } else {
            return 'Ошибка при создании архива.';
        }
    }
}
