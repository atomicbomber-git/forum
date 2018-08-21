<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $timestamps = false;

    const TYPES = [
        'COMMENT',
        'THREAD'
    ];

    public $fillable = [
        'voter_id',
        'votable_id',
        'votable_type',
        'vote_type'
    ];
}
