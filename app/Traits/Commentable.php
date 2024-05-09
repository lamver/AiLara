<?php

declare(strict_types=1);

/**
 * Laravel Commentable Package.
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    /**
     * @return string
     */
    public function commentableModel(): string
    {
        return \App\Models\Comment::class;
    }

    /**
     * @return mixed
     */
    public function comments(): MorphMany
    {
        return $this->morphMany($this->commentableModel(), 'commentable')->whereNull('parent_id');
    }

    /**
     * @return mixed
     */
    public function allComments(): MorphMany
    {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }


    /**
     * @return mixed
     */
    public function activeComments(): MorphMany
    {
        return $this->morphMany($this->commentableModel(), 'commentable')->whereNull('parent_id')->where('active', true);
    }


    /**
     * @return mixed
     */
    public function allActiveComments(): MorphMany
    {
        return $this->morphMany($this->commentableModel(), 'commentable')->where('active', true);
    }


    /**
     * @param array $data
     * @param Model $creator
     * @param Model|null $parent
     *
     * @return static
     */
    public function comment(array $data, Model $creator, Model $parent = null)
    {
        $commentableModel = $this->commentableModel();

        $comment = (new $commentableModel())->createComment($this, $data, $creator);

        if (!empty($parent)) {
            $parent->appendNode($comment);
        }

        return $comment;
    }

    /**
     * @param int $id
     * @param array $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateComment(int $id, array $data, Model $parent = null): mixed
    {
        $commentableModel = $this->commentableModel();

        $comment = (new $commentableModel())->updateComment($id, $data);

        if (!empty($parent)) {
            $parent->appendNode($comment);
        }

        return $comment;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function deleteComment(int $id): bool
    {
        $commentableModel = $this->commentableModel();

        return (bool) (new $commentableModel())->deleteComment($id);
    }

    /**
     * @return mixed
     */
    public function commentCount(): int
    {
        return $this->allComments()->count();
    }
}
