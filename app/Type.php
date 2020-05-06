<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'title'];
}
