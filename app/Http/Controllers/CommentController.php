<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Thread;

class CommentController extends Controller
{
    public function create(Thread $thread)
    {
        $data = request()->all();

        Comment::create([
            'poster_id' => auth()->user()->id,
            'parent_comment_id' => isset($data['parent_comment_id']) ? $data['parent_comment_id'] : NULL,
            'thread_id' => $thread->id,
            'content' => $data['content']
        ]);

        return back();
    }
}
