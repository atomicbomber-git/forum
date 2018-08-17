<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public $fillable = [
        'poster_id',
        'title',
        'content'
    ];

    public function poster()
    {
        return $this->belongsTo(\App\User::class, 'poster_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }
}
