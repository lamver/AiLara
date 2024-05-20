<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentsController extends Controller
{
    private int $numberPaginate = 15;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $commentableId = $request->input('commentableId');

        $comments = $commentableId
            ? Comment::where('commentable_id', $commentableId)->orderBy('id', 'desc')->paginate($this->numberPaginate)
            : Comment::orderBy('id', 'desc')->paginate($this->numberPaginate);

        return view('admin.modules.comments.index', ['comments' => $comments]);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return mixed
     */
    public function setStatus(Request $request, Comment $comment): mixed
    {
        $theComment = $comment::find($request->id);
        return $theComment->update(['active' => $request->status]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {

        $validatedData = $this->validate(
            $request, [
            'title' => 'sometimes|nullable|string|max:255',
            'body' => 'required|string|max:255',
            'active' => 'sometimes|nullable',
            'comment-id' => 'required',
        ]);

        $activeComment = [
            'title' => $validatedData['title'],
            'body' => $validatedData['body'],
            'active' => isset($validatedData['active']) ? Comment::STATUS_ACTIVE : Comment::STATUS_DEACTIVE,
        ];

        (new Comment())->updateComment($validatedData['comment-id'], $activeComment);

        return redirect()->route('admin.comment.index');

    }

    /**
     * @param Comment $comment
     * @param $id
     * @return View
     */
    public function edit(Comment $comment, $id): View
    {
        $theComment = $comment::find($id);
        return view('admin.modules.comments.edit', ['comment' => $theComment]);
    }

    /**
     * @param Comment $comment
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Comment $comment, $id): RedirectResponse
    {
        $theComment = $comment::find($id);

        if ($theComment) {
            $theComment->delete();
        }

        return redirect()->back();
    }

}
