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
        $comment_tree = DB::table('comment_paths')
            ->select(DB::raw('GROUP_CONCAT(ancestor_id ORDER BY ancestor_id) AS hierarchy'), 'users.name AS poster_name', 'comments.content')
            ->join('comments', 'comments.id', '=', 'comment_paths.descendant_id')
            ->join('users', 'users.id', '=', 'comments.poster_id')
            ->groupBy('descendant_id', 'poster_name', 'comments.content')
            ->orderBy('hierarchy')
            ->orderBy('comments.created_at')
            ->get()
            ->map(
                function ($item) {
                    $temp = explode(',', $item->hierarchy);
                    return [
                        'id' => (int) end($temp),
                        'tree_depth' => count($temp),
                        'poster_name' => $item->poster_name,
                        'content' => $item->content
                    ];
                });
                
        return view('thread.detail', [
            'thread' => $thread,
            'comment_tree' => $comment_tree
        ]);
    }
}
