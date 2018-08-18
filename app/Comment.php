<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'thread_id',
        'poster_id',
        'content'
    ];

    public function poster()
    {
        return $this->belongsTo(\App\User::class, 'poster_id');
    }
}
