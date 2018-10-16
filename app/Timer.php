<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    protected $fillable = ['name','user_id','project_id','ended_at','started_at'];

    //protected $dates = ['stopped_at','started_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function scopeMine($query)
    {
        return $query->whereUserId(auth()->user()->id);
    }
    public function scopeRunning($query)
    {
        return $query->whereNull('ended_at');
    }

}
