<?php

namespace App\Console\Commands;

use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Posts;
use App\Models\Tasks;
use Carbon\Carbon;
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
    protected string $mainFileName = "sitemaps/sitemap.xml";

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
/*        ['priority' => '0.5', 'url' => '/test-1'],*/
/*        ['priority' => '0.2', 'url' => '/test-2'],
        ['priority' => '0.5', 'url' => '/test-3'],*/
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
        $sitemapsDir = base_path() . '/public/sitemaps';

        if (!is_dir($sitemapsDir)) {
            mkdir($sitemapsDir, 755, true);
        }

        chmod($sitemapsDir, 755);

        $start = microtime(true);
        $this->info("Старт.");

        $this->sitemapPosts();
        $fileNames = $this->createSiteMapFromTask();
        $this->createSiteMapFromArray($this->siteMapUrls, $this->mainFileName);
        $this->updateMainFile($fileNames);

        $executionTime = microtime(true) - $start;
        $this->info("Скрипт выполнялся $executionTime секунд");
    }

    /**
     * @return void
     */
    public function sitemapPosts(): void
    {
        $categories = Category::query()->get();
        $sitemapPosts = Sitemap::create();

        foreach ($categories as $category) {

            //echo $category->id . PHP_EOL;
            Posts::where(['status' => 'Published'])->where(['post_category_id' => $category->id])->chunk(100, function($posts) use ($sitemapPosts) {
                foreach ($posts as $post) {

                    $setUpdatedAt = Carbon::create($post->updated_at);

                    $sitemapPosts->add(Url::create(Posts::createUrlFromPost($post))
                        ->setLastModificationDate($setUpdatedAt)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.8));

                    //echo $post->title . PHP_EOL;
                }
            });

            $slug = $category->slug;

            if (empty($category->slug)) {
                $slug = Str::slug($category->title);
            }

            $filePath = 'sitemaps/posts_' . $slug . '.xml';

            $this->siteMapUrls[] = [
                'priority' => '0.9', 'url' => $filePath
            ];

            $sitemapPosts->writeToFile(public_path('sitemaps/posts_' . $slug . '.xml'));
        }
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
            ->where("access_type", Tasks::ACCESS_TYPE_PUBLIC)
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
