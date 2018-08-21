<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\CommentPath;
use App\Thread;
use App\Vote;
use DB;

class CommentController extends Controller
{
    public function create(Thread $thread)
    {
        $data = request()->all();

        DB::transaction(function() use($thread, $data) {
            
            $comment = Comment::create([
                'poster_id' => auth()->user()->id,
                'thread_id' => $thread->id,
                'content' => $data['content']
            ]);
            
            CommentPath::create([
                'ancestor_id' => $comment->id,
                'descendant_id' => $comment->id,
                'tree_depth' => 0
            ]);
            
            if (!isset($data['parent_comment_id'])) {
                return;
            }
            
            DB::insert('
                INSERT INTO comment_paths (ancestor_id, descendant_id, tree_depth)
                    SELECT ancestor_id, ?, tree_depth + 1
                    FROM comment_paths
                    WHERE comment_paths.descendant_id = ?
            ', [$comment->id, $data['parent_comment_id']]);

        });

        return back();
    }

    public function upvote($comment)
    {
        $vote_data = [
            'voter_id' => auth()->user()->id,
            'votable_id' => $comment,
            'votable_type' => 'COMMENT',
            'vote_type' => 'UP'
        ];

        $existing_vote = Vote::query()
            ->where($vote_data)
            ->first();
        
        if ($existing_vote != NULL) {
            Vote::where($vote_data)
                ->delete();
            
            return [
                'action' => 'delete'
            ];
        } 

        Vote::create($vote_data);

        return [
            'action' => 'create'
        ];
    }

    public function downvote(Comment $comment)
    {

    }

    public function delete(Comment $comment)
    {
        $descendant_ids = CommentPath::query()
            ->select('descendant_id')
            ->where('ancestor_id', $comment->id)
            ->pluck('descendant_id');

        DB::transaction(function() use($comment, $descendant_ids) {
            CommentPath::whereIn('descendant_id', $descendant_ids)
            ->delete();

            $comment->delete();
        });

        return back();
    }
}
