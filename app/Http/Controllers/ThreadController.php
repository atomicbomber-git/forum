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
                'comment_paths.descendant_id AS id',
                DB::raw('GROUP_CONCAT(ranks.row_number ORDER BY tree_depth DESC) AS ancestor_hierarchy'),
                DB::raw('COUNT(*) AS tree_depth')
            )
            ->joinSub($subQuery, 'ranks', function($join) {
                $join->on('ranks.id', '=', 'comment_paths.ancestor_id');
            })
            ->groupBy('comment_paths.descendant_id');

        $comments = DB::table('comments')
            ->select('comments.id', 'comments.content', 'users.name AS poster_name', 'comments.created_at', 'votes.vote_type', 'comment_tree.tree_depth')
            ->joinSub($commentTree, 'comment_tree', function($join) {
                $join->on('comments.id', '=', 'comment_tree.id');
            })
            ->join('users', 'users.id', '=', 'comments.poster_id')
            ->leftJoin('votes', function($join) {
                $join->on('votes.votable_id', '=', 'comments.id')
                    ->where('votes.voter_id', auth()->user()->id)
                    ->where('votes.votable_type', 'COMMENT');
            })
            ->orderBy('comment_tree.ancestor_hierarchy')
            ->get();
        
        return view('thread.detail', [
            'thread' => $thread,
            'comments' => $comments
        ]);
    }
}
