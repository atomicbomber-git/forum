<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use DB;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::all();

        return view('thread.index', [
            'threads' => $threads
        ]);
    }

    public function create()
    {
        $data = request()->all();

        Thread::create([
            'poster_id' => auth()->user()->id,
            'title' => $data['title'],
            'content' => $data['content'] 
        ]);

        return back();
    }

    public function detail(Thread $thread)
    {
        $root_comment_ids = DB::table('comments')
            ->select('comments.id', DB::raw('COUNT(comments.id) - 1 AS ancestor_count'))
            ->join('comment_paths', 'comment_paths.descendant_id', '=', 'comments.id')
            ->where('comments.thread_id', $thread->id)
            ->groupBy('comments.id')
            ->having('ancestor_count', 0)
            ->pluck('id');

        $comment_tree = DB::table('comments')
            ->select('comment_paths.ancestor_id', 'comments.id', 'users.name AS poster_name', 'comments.content', 'comment_paths.tree_depth')
            ->join('comment_paths', 'comment_paths.descendant_id', '=', 'comments.id')
            ->join('users', 'users.id', '=', 'comments.poster_id')
            ->whereIn('comment_paths.ancestor_id', $root_comment_ids)
            ->where('comments.thread_id', $thread->id)
            ->orderBy('comment_paths.ancestor_id')
            ->orderBy('comment_paths.tree_depth')
            ->get()
            ->groupBy('ancestor_id');

        return view('thread.detail', [
            'thread' => $thread,
            'comment_tree' => $comment_tree
        ]);
    }
}
