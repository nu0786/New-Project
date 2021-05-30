<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'url',
        'parent_id'
    ];
}
