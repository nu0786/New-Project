<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name'
    ];

    public function workshops()
    {
        return $this->hasMany('App\Models\Workshop', 'event_id', 'id');
    }
}
