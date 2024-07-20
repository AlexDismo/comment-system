<?php

namespace App\Services\Comment;

use App\Http\Controllers\File\FileController;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function getComments($page, $sortBy = 'created_at', $order = 'desc'): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Comment::with('files')
            ->whereNull('parent_id')
            ->orderBy($sortBy, $order)
            ->paginate(25, ['*'], 'page', $page);
    }
    public function storeComment(StoreCommentRequest $request)
    {
        $validatedData = $request->validated();
        unset($validatedData['files[]']);
        $comment = Comment::create($validatedData);


        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                app(FileController::class)->upload($file, $comment->id);
            }
        }
        return $comment;
    }
    public function getNestedComments($id, $page)
    {
        $comment = Comment::findOrFail($id);
        $nestedComments = $this->getChildren($comment, $page);
        return $nestedComments;
    }

    private function getChildren($comment, $page)
    {
        $children = $comment->children()->with('files')->orderBy('created_at', 'desc')->paginate(25, ['*'], 'page', $page);

        foreach ($children as $child) {
            $child->children = $this->getChildren($child, $page);
        }

        return $children;
    }
}
