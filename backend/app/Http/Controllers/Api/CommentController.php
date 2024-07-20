<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\Comment\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');
        $comments = Cache::tags('comments')->remember("page.{$page}.sort_by.{$sortBy}.order.{$order}", 60, function () use ($page, $sortBy, $order) {
            return $this->commentService->getComments($page, $sortBy, $order);
        });
        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request)
    {
        $comment=$this->commentService->storeComment($request);
        Cache::tags('comments')->flush();
        return new CommentResource($comment);
    }

    public function show($id, Request $request)
    {
        $page = $request->get('page', 1);
        $comments = $this->commentService->getNestedComments($id, $page);
        return CommentResource::collection($comments);
    }

}
