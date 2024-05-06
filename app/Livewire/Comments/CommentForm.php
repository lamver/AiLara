<?php

namespace App\Livewire\Comments;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Modules\Blog\Posts;

class CommentForm extends Component
{
    /** @var Posts */
    public Posts $post;

    /** @var string */
    public string $title;

    /** @var string */
    public string $content;

    /**
     * @param Posts $post
     * @return void
     */
    public function mount(Posts $post): void
    {
        $this->post = $post;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.comments.comment-form');
    }

    /**
     * @return void
     */
    public function submitComment(): void
    {
        $validatedData = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
        ]);

        $activeComment = [
            'title'   => $validatedData['title'],
            'body'   => $validatedData['content'],
            'active' => true
        ];

        if (Auth::check()) {
            $this->post->comment($activeComment, Auth::user());
            $this->dispatch('newComment');
        }

        $this->reset('content');
        $this->reset('title');

    }

}
