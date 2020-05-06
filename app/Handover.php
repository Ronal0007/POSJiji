<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    protected $fillable = ['fname', 'mname', 'lname','posid', 'user_phone', 'pos_phone', 'kata', 'user_id'];

    /*
     * responsible user
     * */
    public function issuer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * event
     * */
    public function event()
    {
        return $this->morphOne(Event::class, 'event');
    }

    /*
     * Status
     * */
    public function toolsStatus()
    {
        return $this->morphOne(Status::class, 'owner');
    }

    /*
     * user full name
     * */
    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->mname . ' ' . $this->lname;
    }
}
