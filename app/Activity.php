<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['type_id','description', 'user_id'];

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
     * Type
     * */
    public function type()
    {
        return $this->hasOne(Type::class);
    }

    /*
     * responsible user
     * */
    public function issuer()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

abstract class ACTIVITY_TYPE
{
    const FromCustomer = 1;
    const ToTech = 2;
    const FromTech = 3;
}
