<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'project_id', 'user_id', 'external_id', 'description', 'title', 'type', 'start', 'end', 'start_time', 'end_time', 'complete', 'multi_day'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function external()
    {
        return $this->belongsTo(External::class);
    }
}
