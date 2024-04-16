<?php

namespace App\Models\Modules\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    use HasFactory;

    const SOURCE_TYPE_RSS = 1;
    const SOURCE_TYPE_URL = 2;
    const SOURCE_TYPE_WRITE_LONGREAD = 3;

    const STATUS_JOB_CREATED = 0;
    const STATUS_JOB_PROCESSED = 1;
    const STATUS_JOB_FINALE_SUCCESS = 2;
    const STATUS_JOB_ERROR = 3;

    const IMPORT_STATUS = [
        self::STATUS_JOB_CREATED,
        self::STATUS_JOB_PROCESSED,
        self::STATUS_JOB_FINALE_SUCCESS,
        self::STATUS_JOB_ERROR,
    ];

    protected $table = 'blog_import_job';

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function loadPostsList()
    {
        return self::query()->orderBy('id','desc')->paginate();
    }

    /**
     * @return array
     */
    static public function getModelParams()
    {
        return DB::select("show full columns from " . (new self)->table);
    }
}
