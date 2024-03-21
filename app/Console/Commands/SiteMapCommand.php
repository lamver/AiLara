<?php

namespace App\Console\Commands;

use App\Models\Tasks;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SiteMapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var string|mixed
     */
    protected string $hostUrl = "";

    /**
     * @var string
     */
    protected string $priorityDefault = "0.5";

    /**
     * @var string
     */
    protected string $mainFileName = "/sitemap.xml";

    /**
     * Max of number of links in each file.
     * @var int
     */
    protected int $maxChunk = 50000;


    /**
     * [priority => url]
     * @var array|array[]
     */
    protected array $siteMapUrls = [
        ['priority' => '0.5', 'url' => '/test-1'],
        ['priority' => '0.2', 'url' => '/test-2'],
        ['priority' => '0.5', 'url' => '/test-3'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->hostUrl = env('APP_URL', '/');
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $start = microtime(true);
        $this->info("Старт.");

        $fileNames = $this->createSiteMapFromTask();
        $this->createSiteMapFromArray($this->siteMapUrls, $this->mainFileName);
        $this->updateMainFile($fileNames);

        $executionTime = microtime(true) - $start;
        $this->info("Скрипт выполнялся $executionTime секунд");
    }

    /**
     * Create a file sitemap from an array [ [priority => url], ... ]
     *
     * @param array $siteMapUrls
     * @param string $fileName
     * @return void
     */
    public function createSiteMapFromArray(array $siteMapUrls, string $fileName = '/sitemap.xml'): void
    {
        $siteMap = Sitemap::create($this->hostUrl);

        foreach ($siteMapUrls as ['priority' => $priority, 'url' => $url]) {
            $siteMap->add(Url::create($url)->setPriority($priority));
        }

        $siteMap->writeToFile(public_path($fileName));
    }

    /**
     * create siteMaps from task
     *
     * @return array
     */
    public function createSiteMapFromTask(): array
    {
        $fileNames = [];
        $fileCounter = 1;
        $counter = 1;

        Tasks::select('id', 'user_params')
            ->where('status', Tasks::STATUS_CREATED)
            ->chunk($this->maxChunk, function ($tasks) use (&$fileCounter, &$fileNames, &$counter) {

                $siteMap = Sitemap::create($this->hostUrl);

                foreach ($tasks as $task) {

                    $siteMap->add(Url::create(Tasks::createSlug($task))->setPriority($this->priorityDefault));

                    $fileNames['task-' . $fileCounter . '.xml'] = 'task-' . $fileCounter . '.xml';

                    if ($counter++ >= $this->maxChunk) {
                        $siteMap->writeToFile(public_path('task-' . $fileCounter . '.xml'));
                        $siteMap = Sitemap::create($this->hostUrl);
                        $fileCounter++;
                        $counter = 1;
                    }
                }

                $siteMap->writeToFile(public_path('task-' . $fileCounter . '.xml'));

            });

        return $fileNames;
    }

    /**
     * @param array $fileNames
     * @return void
     */
    public function updateMainFile(array $fileNames): void
    {
        foreach ($fileNames as $fileName) {
            $tmp = "<sitemap><loc>" . $this->hostUrl . "/" . $fileName . "</loc></sitemap>\n";

            $content = file_get_contents(public_path($this->mainFileName));
            $position = strpos($content, "</urlset>");

            $newContent = $position === false
                ? $tmp . $content
                : substr_replace($content, $tmp, $position, 0);

            file_put_contents(public_path($this->mainFileName), $newContent);
        }
    }

}
