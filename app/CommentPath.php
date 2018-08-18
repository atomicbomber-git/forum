<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentPath extends Model
{
    public $timestamps = FALSE;

    public $fillable = [
        'ancestor_id',
        'descendant_id',
        'tree_depth'
    ];
}
