<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Domain extends Model
{

    protected $fillable = [
        'name',
        'body',
        'content_length',
        'h1',
        'keywords',
        'description',
        'status_code',
    ];
}
