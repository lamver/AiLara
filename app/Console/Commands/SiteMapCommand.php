<?php

namespace App\Console\Commands;

use App\Models\Tasks;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
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
        $this->createSiteMap($this->siteMapUrls);
        $this->createFromTaskBb();
    }

    /**
     * Create a file sitemap from an array [ [priority => url], ... ]
     *
     * @param array $siteMapUrls
     * @param string $fileName
     * @return void
     */
    public function createSiteMap(array $siteMapUrls, string $fileName = '/sitemap.xml'): void
    {
        $siteMap = Sitemap::create($this->hostUrl);

        foreach ($siteMapUrls as ['priority' => $priority, 'url' => $url]) {
            $siteMap->add(Url::create($url)->setPriority($priority));
        }

        $siteMap->writeToFile(public_path($fileName));
    }

    /**
     * crate siteMaps from task table, chunk it and add links to the main file
     *
     * @return void
     */
    public function createFromTaskBb(): void
    {
        $tasks = Tasks::where('status', Tasks::STATUS_CREATED)->get()->toArray();

        $urls = $this->createSlug($tasks);
        $chunks = array_chunk($urls, $this->maxChunk);

        foreach ($chunks as $key => $chunk) {
            $this->createSiteMap($urls, "/tasks-$key.xml");

            // Add the link to the end of main file
            $tmp = "<sitemap><loc>" . $this->hostUrl . "/tasks-" . $key . ".xml</loc></sitemap>\n";
            $content = file_get_contents(public_path($this->mainFileName));
            $position = strpos($content, "</urlset>");

            $newContent = $position === false
                ? $tmp . $content
                : substr_replace($content, $tmp, $position, 0);

            file_put_contents(public_path($this->mainFileName), $newContent);
        }
    }

    /**
     * Create slugs
     *
     * @param array $params
     * @return array
     */
    public function createSlug(array $params): array
    {
        $returnUrls = [];

        foreach ($params as $key => $param) {
            $userParams = json_decode($param['user_params'], true);
            $userParams = array_map(fn($val) => str_replace("\n", "", Str::limit($val, 200)), $userParams);
            $returnUrls[$key]['url'] = "/" . Str::slug(implode("_", $userParams)) . "/" . $param['id'];
            $returnUrls[$key]['priority'] = $this->priorityDefault;
        }

        return $returnUrls;
    }

}
