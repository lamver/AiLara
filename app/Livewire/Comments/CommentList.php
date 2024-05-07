<?php

namespace App\Livewire\Comments;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Modules\Blog\Posts;
use App\Models\Comment;

class CommentList extends Component
{
    /** @var Object */
    public Object $post;

    /** @var int */
    public int $replyCommentId;

    /** @var string[] */
    public $listeners = [
        'commentUpdate' => 'commentUpdate',
        'deleteComment' => 'deleteComment',
        'replyComment' => 'replyComment',
        'newComment' => 'newComment',
    ];

    /**
     * @param Object $post
     * @return void
     */
    public function mount(Object $post): void
    {
        $this->post = $post;
    }

    public function render(): View
    {
        $tree = $this->buildTree($this->post->allActiveComments()->latest()->get());

        return view('livewire.comments.comment-list', [
            'comments' => $tree,
        ]);
    }

    /**
     * @return void
     */
    public function newComment(): void
    {
        $this->render();
    }

    /**
     * @param $comments
     * @param $parentId
     * @return array
     */
    protected function buildTree($comments, $parentId = null): array
    {
        $tree = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id === $parentId) {
                $children = $this->buildTree($comments, $comment->id);
                if ($children) {
                    $comment->children = $children;
                }
                $tree[] = $comment;
            }
        }

        return $tree;
    }

    /**
     * @param $id
     * @param $body
     * @return void
     */
    public function commentUpdate($id, $body): void
    {
        $this->post->updateComment($id, ['body' => $body]);
    }

    /**
     * @param $id
     * @return void
     */
    public function startReply($id): void
    {
        $this->replyCommentId = (int)$id;
    }

    /**
     * @param $replyComment
     * @return void
     */
    public function replyComment($replyComment): void
    {
        $this->reset('replyCommentId');

        if (Auth::check()) {
            $this->post->comment($replyComment, Auth::user());
        }

    }

    /**
     * @return void
     */
    public function cancelReply(): void
    {
        $this->replyCommentId = null;
    }

    /**
     * @param $commentId
     * @return void
     */
    public function deleteComment($commentId): void
    {
        $theComment = Comment::find($commentId);

        if (Auth::check() && $theComment && Auth::id() === $theComment->creator_id) {
            $theComment->delete();
        }
    }

}
