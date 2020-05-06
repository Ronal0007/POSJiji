<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['event_id', 'event_type', 'pos_id'];

    /*
     * Activity name Handover or returning pos
     * */
    public function event(){
        return $this->morphTo();
    }

    /*
     * Map event to activity
     * */
    public function activity(){
        return $this->event();
    }
}
