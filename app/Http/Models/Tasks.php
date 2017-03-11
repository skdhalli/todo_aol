<?php

namespace App\Http\Models;
use Eloquent;

class Tasks extends Eloquent
{
    protected $table = 'Tasks';
    protected $primaryKey = 'TaskId';
    //protects all attributes against mass assignment
    protected $guarded = array('*');
    public $timestamps = false;

    public function status()
    {
        return $this->hasOne('App\Http\Models\Status', 'StatusId', 'StatusId');
    }
}