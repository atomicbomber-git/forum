<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

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
        $thread->load('comments.poster', 'comments.children.children.children.children');

        // return $thread->comments[0]->children[0]->children;

        return view('thread.detail', [
            'thread' => $thread
        ]);
    }
}
