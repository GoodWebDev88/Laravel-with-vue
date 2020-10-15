<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class External extends Model
{
    protected $fillable = [
        'name', 'color', 'show'
    ];
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
