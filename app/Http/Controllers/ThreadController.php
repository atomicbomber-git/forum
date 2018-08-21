<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use Jenssegers\Date\Date;
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
        DB::statement('SET @row_number = 0');
        $subQuery = DB::table('comments')
            ->select('comments.id', DB::raw('@row_number:=@row_number+1 AS row_number'))
            ->where('thread_id', $thread->id)
            ->orderBy('created_at');

        $commentTree = DB::table('comment_paths')
            ->select(
                'comments.id',
                DB::raw('GROUP_CONCAT(ranks.row_number ORDER BY tree_depth DESC) AS hierarchy'),
                DB::raw('COUNT(*) AS tree_depth'),
                'users.name AS poster_name',
                'comments.content',
                'comments.created_at'
            )
            ->join('comments', 'comments.id', '=', 'comment_paths.descendant_id')
            ->joinSub($subQuery, 'ranks', function($join) {
                $join->on('ranks.id', '=', 'comment_paths.ancestor_id');
            })
            ->join('users', 'users.id', '=', 'comments.poster_id')
            ->where('comments.thread_id', $thread->id)
            ->groupBy('comments.id', 'poster_name', 'comments.content', 'comments.created_at')
            ->orderBy('hierarchy')
            ->get()
            ->map(function($item) {
                $item->created_at = (new Date($item->created_at))->diffForHumans();
                return $item;
            });

        return view('thread.detail', [
            'thread' => $thread,
            'comment_tree' => $commentTree
        ]);
    }
}
