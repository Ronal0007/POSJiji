<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pos extends Model
{
    protected $fillable = ['imei', 'sno', 'user_id'];
    protected $with = ['creator'];
    protected $appends = ['currentstatus', 'currentusernumber', 'currentusername', 'timeInActiveSite'];

    /*
     * Damaged tools
     * */
    public function scopeDamaged($query)
    {
        $all_pos = Pos::all();
        return $all_pos->filter(function (Pos $pos) {
            $damaged = false;
            foreach ($pos->latestToolsStatus() as $tool => $status) {
                if ($status == false) {
                    $damaged = true;
                }
            }
            return $damaged && ($pos->currentstatus == POS_STATUS::returned || $pos->currentstatus == POS_STATUS::Idle);
        })->values();
    }

    /*
     * Damaged tools
     * */
    public function scopeFilter($query, $status)
    {
        $all_pos = Pos::all();

        if ($status == POS_STATUS::Idle) {
            return $all_pos->filter(function (Pos $pos) use ($status) {
                return $pos->currentstatus == $status;
            })->merge($all_pos->filter(function (Pos $pos) {
                return $pos->currentstatus == POS_STATUS::returned;
            }));
        }
        return $all_pos->filter(function (Pos $pos) use ($status) {
            return $pos->currentstatus == $status;
        });
    }

    /*
     * Search
     * */
    public function scopeSearch($query, $search)
    {
        $allPOS = Pos::all();
        return $allPOS->filter(function (Pos $pos) use ($search) {
            return ($pos->currentposid == $search || strtolower($pos->currentkata) == strtolower($search));
        });
    }

    /*
     * events flow
     * */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /*
     * Full events
     * */
    public function fullEvents()
    {
        return $this->events()->with(['activity.issuer']);
    }

    /*
     * original status
     *
     * */
    public function initialToolsStatus()
    {
        return $this->morphOne(Status::class, 'owner');
    }

    /*
     * creator
     * */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * Last HandOver
     * */
    public function lastHandOver()
    {
        $last_handover = $this->events()
            ->where('event_type', "App\\Handover")
            ->orderByDesc('created_at')
            ->first();
        return $last_handover != null ? $last_handover->activity : null;
    }

    /*
     * Last Event
     * */
    public function lastEvent()
    {
        return $this->events()->orderByDesc('created_at')->first();
    }

    /*
     * Current posid according to last event if its handover
     * */
    public function getCurrentPosIdAttribute()
    {
        $last_event = $this->lastEvent() ?? null;
        if ($last_event == null)
            return null;
        if ($last_event->event_type == "App\\Handover") {
            return $last_event->activity->posid;
        }
        return null;
    }

    /*
     * Current kata according to last event if its handover
     * */
    public function getCurrentKataAttribute()
    {
        $last_event = $this->lastEvent() ?? null;
        if ($last_event == null)
            return null;
        if ($last_event->event_type == "App\\Handover") {
            return $last_event->activity->kata;
        }
        return null;
    }

    /*
     * Current Status
     * */
    public function getCurrentStatusAttribute()
    {
        if (!$this->events()->count() > 0) {
            return POS_STATUS::Idle;
        }
        $last_event = $this->lastEvent();
        if ($last_event->event_type == "App\\Handover") {
            return POS_STATUS::Active_Site;
        } else {
            $activity = $last_event->activity;
            if ($activity->type_id == ACTIVITY_TYPE::ToTech) {
                return POS_STATUS::Maintenance;
            } else {
                return POS_STATUS::returned;
            }
        }
    }

    /*
     * status class
     * */
    public function getClassAttribute()
    {
        switch ($this->currentstatus) {
            case POS_STATUS::Idle:
            case POS_STATUS::returned:
                return 'bg-success';
                break;
            case POS_STATUS::Active_Site:
                return 'bg-info';
                break;
            case POS_STATUS::Maintenance:
                return 'bg-danger';
                break;
            default:
                return 'bg-info';
        }
    }

    /*
     * Current user name
     * */
    public function getCurrentUserNameAttribute()
    {
        if ($this->currentstatus == POS_STATUS::Active_Site) {
            $activity = $this->lastEvent()->activity;
            $userName = ucfirst($activity->fname) . " " . ucfirst($activity->mname) . " " . ucfirst($activity->lname);
            return $userName;
        }
        return 'Not active';
    }

    /*
     * Current user number
     * */
    public function getCurrentUserNumberAttribute()
    {
        if ($this->currentstatus == POS_STATUS::Active_Site) {
            $activity = $this->lastEvent()->activity;
            return $activity->user_phone;
        }
        return 'Not active';
    }

    /*
     * active site time
     * */
    public function getTimeInActiveSiteAttribute()
    {
        if ($this->currentstatus == POS_STATUS::Active_Site) {
            $activity = $this->lastEvent()->activity;
            return $activity->created_at->diffInDays() . " Days";
        }
        return 'Not active';
    }

    /*
     * Current POS number
     * */
    public function getLastPOSNumberAttribute()
    {
        if ($this->lastHandOver() != null) {
            return $this->lastHandOver()->pos_phone;
        }
        return 'Not active';
    }

    /*
     * Latest tool statuses
     */
    public function latestToolsStatus()
    {
        if ($this->events()->count() > 1) { //has more than handover
            return $this->lastEvent()->activity->toolsstatus->tools;
        }
        return $this->initialToolsStatus->tools;
    }

    /*
     * Latest tool statuses holder
     */
    public function latestToolsStatusHolder()
    {
        if ($this->events()->count() > 1) { //has more than handover
            return $this->lastEvent()->activity->toolsstatus();
        }
        return $this->initialToolsStatus();
    }

    /*
     * Check if has damaged tool
     * */
    public function getHasDamageAttribute()
    {
        foreach ($this->latestToolsStatus() as $tool => $status) {
            if (!$status) {
                return true;
            }
        }
        return false;
    }

    /*
     * return pos from
     * */
    public function getReturnFromAttribute()
    {
        if ($this->currentstatus == POS_STATUS::Maintenance) {
            return "Technician";
        }
        return "Customer";
    }

}

abstract class POS_STATUS
{
    const Idle = 'Idle';
    const returned = "Returned";
    const Active_Site = 'Active Site';
    const Maintenance = 'Maintenance';
}
