<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'user_id'
    ];
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
