<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['owner_id', 'owner_type', 'tools'];
    protected $casts = ['tools' => 'json'];


    /*
     * tool
     * */
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
