<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Workshop extends Model
{
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'start',
        'end',
        'event_id',
        'name'
    ];

    public function events()
    {
        return $this->hasMany('App\Models\Event', 'id', 'event_id');
    }
}
